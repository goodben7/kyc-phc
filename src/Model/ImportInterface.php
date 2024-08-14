<?php

namespace App\Model;

interface ImportInterface
{
    public function getId(): ?string;

    public function getType(): ?string;

    public function setType(string $type): static;

    public function getMethod(): ?string;

    public function setMethod(string $method): static;

    public function getStatus(): ?string;

    public function setStatus(string $status): static;

    public function getCreatedBy(): ?string;

    public function setCreatedBy(string $createdBy): static;

    public function getCreatedAt(): ?\DateTimeImmutable;

    public function setCreatedAt(\DateTimeImmutable $createdAt): static;

    public function getMessage(): ?string;

    public function setMessage(?string $message): static;

    public function getLoaded(): ?int;

    public function setLoaded(int $loaded): static;

    public function getTreated(): ?int;

    public function setTreated(int $treated): static;

    public function getData1(): ?string;

    public function setData1(?string $data1): static;

    public function getData2(): ?string;

    public function setData2(?string $data2): static;

    public function getData3(): ?string;

    public function setData3(?string $data3): static;

    public function getData4(): ?string;

    public function setData4(?string $data4): static;

    public function getData5(): ?string;

    public function setData5(?string $data5): static;

    public function getData6(): ?string;

    public function setData6(?string $data6): static;

    public function getDescription(): ?string;

    public function setDescription(?string $description): static;
}