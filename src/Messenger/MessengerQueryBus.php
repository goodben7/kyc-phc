<?php
namespace App\Messenger;


use App\Message\Query\QueryInterface;
use App\Message\Query\QueryBusInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function ask(QueryInterface $query): mixed
    { 
        try {
            return $this->handle($query);
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
