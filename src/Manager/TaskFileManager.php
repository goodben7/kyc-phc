<?php

namespace App\Manager;

use App\Model\TaskFileManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class TaskFileManager implements TaskFileManagerInterface
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir . '/public/media';
    }

    public function save(UploadedFile $file): string
    {
        $task = md5(uniqid()) . '.' . $file->getClientOriginalExtension();
        $file->move($this->projectDir, $task);
        return $task;
    }

    public function getPath(string $fileId): string
    {
        return $this->projectDir . '/' . $fileId;
    }

    public function getSize(string $fileId): int
    {
        $file = new File($this->getPath($fileId));
        return $file->getSize();
    }
}
