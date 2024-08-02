<?php

namespace App\Model;

interface TaskRunnerInterface
{
    function support(string $type): bool;
    
    function run(TaskInterface $task): void;
}