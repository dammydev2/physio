<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This code is partially based on the Rack-Cache library by Ryan Tomayko,
 * which is released under the MIT license.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\HttpCache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Store implements all the logic for storing cache metadata (Request and Response headers).
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Store implements StoreInterface
{
    protected $root;
    private $keyCache;
    private $locks;

    /**
     * @throws \RuntimeException
     */
    public function __construct(string $root)
    {
        $this->root = $root;
        if (!file_exists($this->root) && !@mkdir($this->root, 0777, true) && !is_dir($this->root)) {
            throw new \RuntimeException(sprintf('Unable to create the store directory (%s).', $this->root));
        }
        $this->keyCache = new \SplObjectStorage();
        $this->locks = [];
    }

    /**
     * Cleanups storage.
     */
    public function cleanup()
    {
        // unlock everything
        foreach ($this->locks as $lock) {
            flock($lock, LOCK_UN);
            fclose($lock);
        }

        $this->locks = [];
    }

    /**
     * Tries to lock the cache for a given Request, without blocking.
     *
     * @return bool|string true if the lock is acquired, the path to the current lock otherwise
     */
    public function lock(Request $request)
    {
        $key = $this->getCacheKey($request);

        if (!isset($this->locks[$key])) {
            $path = $this->getPath($key);
            if (!file_exists(\dirname($path)) && false === @mkdir(\dirname($path), 0777, true) && !is_dir(\dirname($path))) {
                return $path;
            }
            $h = fopen($path, 'cb');
            if (!flock($h, LOCK_EX | LOCK_NB)) {
                fclose($h);

                return $path;
            }

            $this->locks[$key] = $h;
        }

        return true;
    }

    /**
     * Releases the lock for the given Request.
     *
     * @return bool False if the lock file does not exist or cannot be unlocked, true otherwise
     */
    public function unlock(Request $request)
    {
        $key = $this->getCacheKey($request);

        if (isset($this->locks[$key])) {
            flock($this->locks[$key], LOCK_UN);
            fclose($this->locks[$key]);
            unset($this->locks[$key]);

            return true;
        }

        return false;
    }

    public function isLocked(Request $request)
    {
        $key = $this->getCacheKey($request);

        if (isset($this->locks[$key])) {
            return true; // shortcut if lock held by this process
        }

        if (!file_exists($path = $this->getPath($key))) {
            return false;
        }

        $h = fopen($path, 'rb');
        flock($h, LOCK_EX | LOCK_NB, $wouldBlock);
        flock($h, LOCK_UN); // release the lock we just acquired
        fclose($h);

        return (bool) $wouldBlock;
    }

    /**
     * Locates a cached Response for the Request provided.
     *
     * @return Response|null A Response instance, or null if no cache entry was found
     */
    public function lookup(Request $request)
    {
        $key = $this->getCacheKey($request);

        if (!$entries = $this->getMetadata($key)) {
            return;
        }

        // find a cached entry that matches the request.
        $match = null;
        foreach ($entries as $entry) {
            if ($this->requestsMatch(isset($entry[1]['vary'][0]) ? implode(', ', $entry[1]['vary']) : '', $request->headers->all(), $entry[0])) {
                $match = $entry;

                break;
            }
        }

        if (null === $match) {
            return;
        }

        $headers = $match[1];
        if (file_exists($body = $this->getPath($headers['x-content-digest'][0]))) {
            return $this->restoreResponse($headers, $body);
        }

        // TODO the metaStore referenced an entity that doesn't exist in
        // the entityStore. We definitely want to return nil but we should
        // also purge the entry from the meta-store when this is detected.
    }

    /**
     * Writes a cache entry to the store for the given Request and Response.
     *
     * Existing entries are read and any that match the response are removed. This
     * method calls write with the new list of cache entries.
     *
     * @return string The key under which the response is stored
     *
     * @throws \RuntimeException
     */
    public function write(Request $request, Response $response)
    {
        $key = $this->getCacheKey($request);
        $storedEnv = $this->persistRequest($request);

        // write the response body to the entity store if this is the original response
        if (!$response->headers->has('X-Content-Digest')) {
            $digest = $this->generateContentDigest($response);

            if (false === $this->save($digest, $response->getContent())) {
                throw new \RuntimeException('Unable to store the entity.');
            }

            $response->headers->set('X-Content-Digest', $digest);

            if (!$response->headers->has('Transfer-Encoding')) {
                $response->headers->set('Content-Length', \strlen($response->getContent()));
            }
        }

        // read existing cache entries, remove non-varying, and add this one to the list
        $entries = [];
        $vary = $response->headers->get('vary');
        foreach ($this->getMetadata($key) as $entry) {
            if (!isset($entry[1]['va