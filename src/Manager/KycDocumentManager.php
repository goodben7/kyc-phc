<?php

namespace App\Manager;

use App\Entity\KycDocument;
use App\Model\NewKycDocumentModel;
use App\Service\UploadedBase64File;
use Doctrine\ORM\EntityManagerInterface;

class KycDocumentManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private UploadedBase64File $service
    )
    {
    }

    public function create(NewKycDocumentModel $model): KycDocument
    {
        $kycDocument = new KycDocument();

        $file = $this->service->handleBase64Image($model->file);

        $kycDocument->setType($model->documentType);
        $kycDocument->setDocumentRefNumber($model->documentRefNumber);
        $kycDocument->setAgent($model->agent);
        $kycDocument->setFile($file);
        $kycDocument->setExternalReferenceId($model->externalReferenceId);  
        
        $this->em->persist($kycDocument);
        $this->em->flush();

        return $kycDocument;
    }
}