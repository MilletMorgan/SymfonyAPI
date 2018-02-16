<?php

namespace App\Manager;

use App\Entity\Image;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageManager
{
    public function __construct()
    {
    }

    public function uploadService()
    {
        $image = new Image;

        $file = $image->getImage();
        $id = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getParameter('images_directory'), $id);

        return $this->redirect($this->generateUrl('download', array('id' => $id)));
    }

    public function downloadService($route, $id)
    {
        if (is_file($route . $id)) {
            $response = new BinaryFileResponse($route . $id);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
            
            return $response;
        }

        return false;
    }

    public function deleteService($route, $id)
    {
        if (is_file($route . $id)) {
            unlink($route . $id);

            return true;
        }

        return false;
    }
}
