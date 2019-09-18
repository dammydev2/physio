<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Routing\Generator;

use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContextAwareInterface;

/**
 * UrlGeneratorInterface is the interface that all URL generator classes must implement.
 *
 * The constants in this interface define the different types of resource references that
 * are declared in RFC 3986: http://tools.ietf.org/html/rfc3986
 * We are using the term "URL" instead of "URI" as this is more common in web applications
 * and we do not need to distinguish them as the difference is mostly semantical and
 * less technical. Generating URIs, i.e. representation-independent resource ide