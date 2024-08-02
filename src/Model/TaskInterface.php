<?php

namespace App\Model;

interface TaskInterface
{
    function getId(): ?string;

    function getType(): ?string;

    function getMethod(): ?string;

    function getStatus(): ?string;

    function getData(): array;

    function getCreatedAt(): ?\DateTimeImmutable;

    function getCreatedBy(): ?string;

    function getUpdatedAt(): ?\DateTimeImmutable;

    function getUpdatedBy(): ?string;

    function getSynchronizedBy(): ?string;

    function getSynchronizedAt(): ?\DateTimeImmutable;

    function getDataValue(string $key, mixed $defaultValue = null): mixed;

    function getMessage(): ?string;

    function getData1(): ?string;

    function getData2(): ?string;

    function getData3(): ?string;

    function getData4(): ?string;

    function getData5(): ?string;

    function getData6(): ?string;

    function getExternalReferenceId(): ?string;
}