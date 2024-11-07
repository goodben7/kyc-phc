<?php

namespace App\Message\Command;

use App\Model\ImportInterface;
use App\Event\EventMessageInterface;

class CreateTaskCommand implements EventMessageInterface 
{
    public function __construct(public ImportInterface $import){}

}