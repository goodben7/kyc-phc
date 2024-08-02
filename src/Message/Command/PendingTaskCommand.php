<?php
namespace App\Message\Command;



class PendingTaskCommand implements CommandInterface 
{
    public function __construct(
        public string $taskId
    )
    {} 
}