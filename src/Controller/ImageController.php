<?php

namespace App\Controller;

use App\Manager\ImageManager;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends FOSRestController
{
    /**
     * @Post("/images")
     */
    public function uploadAction(Request $request, ImageManager $imageManager)
    {
        $file = $request->files->get('image');

        $result = $imageManager->moveUploadedFile($file);

        if ($result == true) {
            return new Response('true');
        }
        return new Response('false');
    }

    /**
     * @Get("/images/{id}")
     */
    public function downloadAction(Request $request, ImageManager $imageManager)
    {
        $id = $request->get('id');

        $result = $imageManager->download($id);

        if ($result == true) {
            return new Response('true');
        }
        return new Response('false');
    }

    /**
     * @Delete("/images/{id}")
     */
    public function deleteAction(Request $request, ImageManager $imageManager)
    {
        $id = $request->get('id');

        $result = $imageManager->delete($id);

        if ($result == true) {
            return new Response('true');
        }
        return new Response('false');
    }
}
