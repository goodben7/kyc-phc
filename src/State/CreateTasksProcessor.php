<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Manager\TaskManager;
use App\Model\NewTaskModel;

class CreateTasksProcessor implements ProcessorInterface
{

    public function __construct(private TaskManager $manager)
    {
        
    }

    /**
     * @param \App\Dto\CreateTaskstDto $data 
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        foreach($data as $row){
            foreach($row as $value)
            {
                $model = new NewTaskModel();
                $model->type = $value->type;
                $model->method = $value->method;
                $model->data = $value->data;
                $model->createdBy = $value->createdBy;
                $model->externalReferenceId = $value->externalReferenceId;
                $model->createdAt = $value->createdAt;
                $model->data1 = $value->data1;
                $model->data2 = $value->data2;
                $model->data3 = $value->data3;
                $model->data4 = $value->data4;
                $model->data5 = $value->data5;
                $model->data6 = $value->data6;

                $this->manager->create($model);
            }   
        }
        
        return $model;
    }
}