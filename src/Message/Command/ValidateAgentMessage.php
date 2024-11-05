<?php
namespace App\Message\Command;

use App\Entity\Agent;
use App\Event\EventMessageInterface;



class ValidateAgentMessage implements EventMessageInterface 
{
    public function __construct(
        public Agent $agent,
        public string $userId,
        public string $identificationNumber
    )
    {} 
}