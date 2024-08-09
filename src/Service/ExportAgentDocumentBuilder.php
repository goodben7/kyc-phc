<?php

namespace App\Service;

use App\ValueObject\ReportResult;
use App\Repository\KycDocumentRepository;

class ExportAgentDocumentBuilder
{
    public function __construct(private KycDocumentRepository $repository)
    {   
    }
    
    public function buildAll() : ReportResult
    {        
        $data = $this->repository->findDocuments();

        $datas = [];
        $datas[] = $this->buildHeaders();

        foreach ($data as $row) {
            $datas[] = $this->flattenRow($row);
        }

        return new ReportResult($datas);
    }

    private function buildHeaders(): array
    {
        return [
            'ID',                                 
            'TYPE',                              
            'NUMÉRO DE RÉFÉRENCE DU DOCUMENT',   
            'STATUT',                        
            'CHEMIN DU FICHIER',              
            'TAILLE DU FICHIER',                 
            'ID DE L\'AGENT',                  
            'NOM COMPLET DE L\'AGENT',
            'MATRICULE'                                                        
        ];
    }

    private function flattenRow(array $row): array
    {
        return [
            $row['id'],                                
            $row['type'],                             
            $row['documentRefNumber'],                    
            $row['status'],                           
            $row['filePath'],                         
            $row['fileSize'], 
            $row['agentId'] ?? 'Non renseigné',        
            $row['agentFullName'] ?? 'Non renseigné',
            $row['identificationNumber'] ?? 'Non renseigné'                                                                             
        ];
    }

}