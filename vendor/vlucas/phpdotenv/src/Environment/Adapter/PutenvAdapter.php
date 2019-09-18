<?php

/**
 * @license Apache 2.0
 */

namespace Swagger;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;
use Exception;

// Load all whitelisted annotations
AnnotationRegistry::registerLoader(function ($class) {
    if (Analyser::$whitelist === false) {
        $whitelist = ['Swagger\Annotations\\'];
    } else {
        $whitelist = Analyser::$whitelist;
    }
    foreach ($whitelist as $namespace) {
        if (strtolower(substr($class, 0, strlen($namespace))) === strtolower($namespace)) {
            $loaded = class_exists($class);
            if (!$loaded && $namespace === 'Swagger\Annotations\\') {
                if (in_array(strtolower(substr($class, 20)), ['model', 'resource', 'api'])) { // Detected an 1.x annotation?
                    throw new Exception('The annotation @SWG\\' . substr($class, 20) . '() is deprecated. Found in ' . Analyser::$context . "\nFor more information read the migrati