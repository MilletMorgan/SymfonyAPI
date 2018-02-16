<?php

namespace App\Manager;

use App\Service\FileUploader;
use App\Service\FileDownload;
use App\Service\FileDelete;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageManager
{
    private $fileUploader;
    private $fileDownload;
    private $fileDelete;

    public function __construct(FileUploader $fileUploader, FileDownload $fileDownload, FileDelete $fileDelete)
    {
        $this->FileUploader = $fileUploader;
        $this->FileDownload = $fileDownload;
        $this->FileDelete = $fileDelete;
    }

    public function uploadService()
    {
        $uploadService = $this->FileUploader->uploadService();
        return $uploadService;
    }

    public function downloadService()
    {
        $downloadService = $this->FileDownload->downloadService();
        return $downloadService;
    }

    public function deleteService()
    {
        $deleteService = $this->FileUDelete->deleteService();
        return $deleteService;
    }
}
