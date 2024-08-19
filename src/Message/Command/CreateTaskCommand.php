<?php

namespace App\Message\Command;

use App\Model\ImportInterface;

class CreateTaskCommand implements CommandInterface 
{
    public function __construct(public ImportInterface $import){}

}