<?php
namespace App\Messenger;

use App\Message\Command\CommandInterface;
use App\Message\Command\CommandBusInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class MessengerCommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function dispatch(CommandInterface $command): mixed
    {
        try {
            return $this->handle($command);
        } catch (HandlerFailedException $e) {
            /** @var array{0: \Throwable} $exceptions */
            $exceptions = $e->getWrappedExceptions();
            if (count($exceptions) > 0) {
                throw $exceptions[0];
            } else {
                throw $e;
            }
        }
    }
}