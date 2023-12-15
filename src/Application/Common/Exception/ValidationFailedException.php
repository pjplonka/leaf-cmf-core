<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationFailedException extends Exception
{
    public function __construct(public readonly ConstraintViolationListInterface $violations)
    {
        parent::__construct();
    }
}