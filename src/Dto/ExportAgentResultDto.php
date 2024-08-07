<?php

namespace App\Dto;

use App\Model\DatasetInterface;
use Symfony\Component\Uid\Uuid;
use App\ValueObject\ReportResult;
use Symfony\Component\Serializer\Annotation\Groups;

class ExportAgentResultDto implements DatasetInterface
{ 
    #[Groups(['export_agent:get'])]
    public Uuid $id;

    #[Groups(['export_agent:get'])]
    public array $datasets = [];

    public function __construct() 
    {
        $this->id = Uuid::v1();
    }

    public static function from(ReportResult $result): static {
        $r = new self();
        $r->datasets = \iterator_to_array($result->getIterator());
        return $r;
    }

    public function getDatasets(): iterable
    {
        return $this->datasets;
    }
}