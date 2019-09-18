<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Translation\Tests\Catalogue;

use Symfony\Component\Translation\Catalogue\TargetOperation;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\MessageCatalogueInterface;

class TargetOperationTest extends AbstractOperationTest
{
    public function testGetMessagesFromSingleDomain()
    {
        $operation = $this->createOperation(
            new MessageCatalogue('en', ['messages' => ['a' => 'old_a', 'b' => 'old_b']]),
            new MessageCatalogue('