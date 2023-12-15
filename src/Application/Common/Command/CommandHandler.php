<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common\Command;

interface CommandHandler
{
    public function handles() : string;
}