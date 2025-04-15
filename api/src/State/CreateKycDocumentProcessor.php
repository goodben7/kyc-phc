<?php

namespace App\State;

use App\Model\NewKycDocumentModel;
use ApiPlatform\Metadata\Operation;
use App\Manager\KycDocumentManager;
use ApiPlatform\State\ProcessorInterface;

class CreateKycDocumentProcessor implements ProcessorInterface
{
    public function __construct(
        private KycDocumentManager $manager
    )
    {
        
    }

    /**
     * @param \App\Dto\CreateKycDocumentDto $data 
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $model = new NewKycDocumentModel();
       
        $model->agent = $data->agent;
        $model->file = $data->file;
        $model->documentType = $data->documentType;
        $model->documentRefNumber = $data->documentRefNumber;
        $model->externalReferenceId = $data->externalReferenceId;

        return $this->manager->create($model);
    }
}