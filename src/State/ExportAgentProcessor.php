<?php

namespace App\State;

use App\Dto\ExportAgentResultDto;
use ApiPlatform\Metadata\Operation;
use App\Service\ExportAgentBuilder;
use ApiPlatform\State\ProcessorInterface;

class ExportAgentProcessor implements ProcessorInterface
{

    public function __construct(private ExportAgentBuilder $manager)
    {   
    }

    /**
     * @return mixed
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        
        if(!is_null($data->filters)){
            $result = $this->manager->buildAll($data->filters); 
            return ExportAgentResultDto::from($result);
        }
        $result = $this->manager->buildAll(); 
        return ExportAgentResultDto::from($result);
    }
}