<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Processors;

use Swagger\Annotations\Path;
use Swagger\Logger;
use Swagger\Context;
use Swagger\Analysis;

/**
 * Build the swagger->paths using the detected @SWG\Path and @SWG\Operations (like @SWG\Get, @SWG\Post, etc)
 */
class BuildPaths
{
    public function __invoke(Analysis $analysis)
    {
        $paths = [];
        // Merge @SWG\Paths with the same path.
        foreach ($analysis->swagger->paths as $annotation) {
            if (empty($annotation->path)) {
                Logger::notice($annotation->identity() . ' is missing required property "path" in ' . $annotation->_context);
            } elseif (isset($paths[$annotation->path])) {
                $paths[$annotation->path]->mergeProperties($annotation);
                $analysis->annotations->detach($annotation);
            } else {
                $paths[$annotation->path] = $annotation;
        