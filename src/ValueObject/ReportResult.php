<?php

namespace App\ValueObject;

use Traversable;
use IteratorAggregate;

class ReportResult implements IteratorAggregate
{
    public function __construct(private array $datasets) 
    {
    }
    
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->datasets);
    }
}