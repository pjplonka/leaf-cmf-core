<?php declare(strict_types=1);

namespace Leaf\Core\Core\Exception;

use Exception;

final class FieldAlreadyExistException extends Exception
{
    public static function create(string $name): self
    {
        return new self(sprintf('Field with name `%s` already exist.', $name));
    }
}