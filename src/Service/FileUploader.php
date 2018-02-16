<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Image;

class FileUploader
{
    public function uploadService(UploadedFile $file)
    {
        $image = new Image;

        $file = $image->getImage();
        $id = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getParameter('images_directory'), $id);

        return $this->redirect($this->generateUrl('download', array('id' => $id)));
    }
}
