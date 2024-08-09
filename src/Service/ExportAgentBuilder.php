<?php

namespace App\Service;

use App\ValueObject\ReportResult;
use App\Repository\AgentRepository;

class ExportAgentBuilder
{
    public function __construct(private AgentRepository $repository)
    {   
    }
    
    public function buildAll() : ReportResult
    {
        $data = $this->repository->findAgents();

        // Construire l'en-tête
        $datas = [];
        $datas[] = $this->buildHeaders();
        
        // Ajouter les données après les avoir correctement structurées
        foreach ($data as $row) {
            $datas[] = $this->flattenRow($row);
        }

        return new ReportResult($datas);
    }

    private function buildHeaders(): array
    {
        return [
            'MATRICULE', 'NOM', 'POSTNOM', 'PRENOM', 'CATEGORIE', 'FONCTION', 'LIEU AFFECTATION', 'SITE',
            'ANCIEN MATRICULE', 'NATIONALITE', 'LIEU DE NAISSANCE', 'DATE DE NAISSANCE', 'STATUT KYC', 'ÉTAT CIVIL', 'GENRE', 
            'STATUT', 'N° TEL.', 'N° TEL. 2', 'ADRESSE PHYSIQUE', 'ADRESSE PHYSIQUE 2', 
            'SALAIRE NET CONTRACTUEL USD', 'SALAIRE NET CONTRACTUEL CDF', 'DATE D\'EMBAUCHE', 'TYPE DE CONTRAT', 
            'DATE FIN CONTRAT ET/OU PERIODE D\'ESSAI', 'ANNOTATION', 'N° CNSS', 'N° NIF', 'N° RIB', 'DEPENDANTS', 
            'PERSONNE DE CONTACT EN CAS D\'URGENCE', 'FICHE SIGNALETIQUE', 'CONTRAT VISE ONEM', 'ACTE DE NAISSANCE', 'ACTE DE MARIAGE'
        ];

    }

    private function flattenRow(array $row): array
    {
        return [
            $row['identificationNumber'],
            $row['lastName'],
            $row['postName'],
            $row['firstName'],
            $row['category'],
            $row['functionTitle'],
            $row['affectedLocation'],
            $row['site'],
            $row['oldIdentificationNumber'],
            $row['country'],
            $row['placeBirth'],
            $this->formatDate($row['birthday']),
            $row['kycStatus'],
            $row['maritalStatus'],
            $row['gender'],
            $row['status'],
            $row['contact'],
            $row['contact2'],
            $row['address'],
            $row['address2'],
            $row['contractualNetPayUsd'],
            $row['contractualNetPayCdf'],
            $this->formatDate($row['dateHire']),
            $row['contratType'],
            $this->formatDate($row['endContractDate']),
            $row['annotation'],
            $row['socialSecurityId'],
            $row['taxIdNumber'],
            $row['bankAccountId'],
            $row['dependent'],
            $row['emergencyContactPerson'],
            $row['factSheet'],
            $row['onemValidatedContract'],
            $row['birthCertificate'],
            $row['marriageLicense']
        ];
    }

    private function formatDate(\DateTimeInterface|string|null $date): ?string
    {
        if ($date === null) {
            return null;
        }

        if ($date instanceof \DateTimeInterface) {
            return $date->format('d-m-Y');
        }

        $formattedDate = \DateTimeImmutable::createFromFormat('Y-m-d', $date);

        return $formattedDate ? $formattedDate->format('d-m-Y') : $date;
    }

}