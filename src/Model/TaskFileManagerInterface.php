<?php

namespace App\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface TaskFileManagerInterface
{
    public function save(UploadedFile $file): string;

    public function getPath(string $fileId): string;

    public function getSize(string $fileId): int;
}