<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common\Command;

use Leaf\Core\Application\Common\Event\EventBus;
use RuntimeException;
use UnexpectedValueException;

final class DefaultCommandBus implements CommandBus
{
    /** @var CommandHandler[] */
    private array $handlers;

    public function __construct(private  readonly EventBus $eventBus, CommandHandler ...$handlers)
    {
        foreach ($handlers as $handler) {
            if (!class_exists($handler->handles())) {
                throw new UnexpectedValueException(sprintf('Command "%s" handled by handler does not exists', $handler->handles()));
            }

            if (!is_callable($handler)) {
                throw new UnexpectedValueException(sprintf('Handler "%s" does not implement __invoke() method.', get_class($handler)));
            }

            $this->handlers[] = $handler;
        }
    }

    public function handle(Command $command): void
    {
        $handler = $this->findHandler(get_class($command));

        $handler($command);

        $this->eventBus->publish();
    }

    private function findHandler(string $commandClass): CommandHandler|callable
    {
        foreach ($this->handlers as $handler) {
            if ($handler->handles() === $commandClass) {
                return $handler;
            }
        }

        throw new RuntimeException(sprintf('Handler form command "%s" does not exist', $commandClass));
    }
}