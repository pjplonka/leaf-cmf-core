<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common\Command;

interface CommandBus
{
    public function handle(Command $command): void;
}