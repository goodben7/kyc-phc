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
        $data = $this->repository->findAll();

        $rows = [];
        $rows[] = $this->buildHeaders();
        foreach ($data as $row) {
            $rows[] = [
                'id' => $row->getId(),
                'firstName' => $row->getFirstName(),
                'lastName' => $row->getLastName(),
                'postName' => $row->getPostName(),
                'fullName' => $row->getFullName(),
                'country' => $row->getCountry(),
                'birthday' => $row->getBirthday(),
                'kycStatus' => $row->getKycStatus(),
                'maritalStatus' => $row->getMaritalStatus(),
                'gender' => $row->getGender(),
                'status' => $row->getStatus(),
                'createdAt' => $row->getCreatedAt(),
                'externalReferenceId' => $row->getExternalReferenceId(),
                'createdBy' => $row->getCreatedBy(),
                'updatedAt' => $row->getUpdatedAt(),
                'identificationNumber' => $row->getIdentificationNumber(),
                'address' => $row->getAddress(),
                'address2' => $row->getAddress2(),
                'deleted' => $row->isDeleted(),
                'validatedBy' => $row->getValidatedBy(),
                'validatedAt' => $row->getValidatedAt(),
                'contractualNetPayUsd' => $row->getContractualNetPayUsd(),
                'contractualNetPayCdf' => $row->getContractualNetPayCdf(),
                'dateHire' => $row->getDateHire(),
                'contratType' => $row->getContratType(),
                'endContractDate' => $row->getEndContractDate(),
                'annotation' => $row->getAnnotation(),
                'placeBirth' => $row->getPlaceBirth(),
                'socialSecurityId' => $row->getSocialSecurityId(),
                'taxIdNumber' => $row->getTaxIdNumber(),
                'bankAccountId' => $row->getBankAccountId(),
                'dependent' => $row->getDependent(),
                'emergencyContactPerson' => $row->getEmergencyContactPerson(),
                'factSheet' => $row->isFactSheet(),
                'onemValidatedContract' => $row->IsOnemValidatedContract(),
                'birthCertificate' => $row->IsBirthCertificate(),
                'marriageLicense' => $row->IsMarriageLicense(),
                'site' => $row->getSite() ? $row->getSite()->__toString() : null,
                'category' => $row->getCategory() ? $row->getCategory()->__toString() : null,
                'functionTitle' => $row->getFunctionTitle() ? $row->getFunctionTitle()->__toString() : null,
                'affectedLocation' => $row->getAffectedLocation() ? $row->getAffectedLocation()->__toString() : null,
            ];
        }

        return new ReportResult($rows);
    }

    private function buildHeaders(): array
    {
        return [
            'ID', 'First Name', 'Last Name', 'Post Name', 'Full Name', 'Country', 'Birthday',
            'KYC Status', 'Marital Status', 'Gender', 'Status', 'Created At', 'External Reference ID',
            'Created By', 'Updated At', 'Identification Number', 'Address', 'Address 2', 'Deleted',
            'Validated By', 'Validated At', 'Contractual Net Pay USD', 'Contractual Net Pay CDF',
            'Date Hire', 'Contract Type', 'End Contract Date', 'Annotation', 'Place Birth',
            'Social Security ID', 'Tax ID Number', 'Bank Account ID', 'Dependent', 'Emergency Contact Person',
            'Fact Sheet', 'ONEM Validated Contract', 'Birth Certificate', 'Marriage License',
            'Site', 'Category', 'Function Title', 'Affected Location'
        ];
    }

}