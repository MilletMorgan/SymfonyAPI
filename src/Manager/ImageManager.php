<?php

namespace App\Manager;

use App\Entity\Image;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImageManager extends Controller
{
    private $targetDir;

    public function __construct(string $targetDir) 
    {
        $this->targetDir = $targetDir;
        var_dump($targetDir);
    }

    public function upload($file)
    {
        $route = $this->getParameter('images_directory');
        $id = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($route, $id);

        return true;
    }

    public function download($route, $id)
    {        
        if (is_file($route . $id)) {
            $response = new BinaryFileResponse($route . $id);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
            
            return $response;
        }

        return false;
    }

    public function delete($route, $id)
    {
        if (is_file($route . $id)) {
            unlink($route . $id);
            return true;
        }

        return false;
    }
}
