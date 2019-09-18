<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\HttpCache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Abstract class implementing Surrogate capabilities to Request and Response instances.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
abstract class AbstractSurrogate implements SurrogateInterface
{
    protected $contentTypes;
    protected $phpEscapeMap = [
        ['<?', '<%', '<s', '<S'],
        ['<?php echo "<?"; ?>', '<?php echo "<%"; ?>', '<?php echo "<s"; ?>', '<?php echo "<S"; ?>'],
    ];

    /**
     * @param array $contentTypes An array of content-type that should be parsed for Surrogate information
     *                            (default: text/html, text/xml, application/xhtml+xml, and application/xml)
     */
    public function __construct(array $contentTypes = ['text/html', 'text/xml', 'application/xhtml+xml', 'applicatio