<?php

namespace App\Service;

use App\Entity\Image;

class FileDownloader
{
    public function downloadService()
    {
        $response = new BinaryFileResponse($route . $id);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        
        return $response;
    }
}
