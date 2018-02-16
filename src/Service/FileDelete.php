<?php

namespace App\Service;

use App\Entity\Image;

class FileUploader
{
    public function deleteService()
    {
        if (is_file($route . $id)) {
            unlink($route . $id);

            return new Response('TRUE');
        }
        
        return new Response('FALSE');
    }
}
