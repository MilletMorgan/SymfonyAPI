<?php

namespace App\Controller;

use App\Form\UploadType;
use App\Form\DownloadType;
use App\Form\DeleteType;
use App\Entity\Image;
use App\Manager\ImageManager;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\Mapping\Annotation;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;

use Liip\ImagineBundle\Binary\BinaryInterface;
use Liip\ImagineBundle\Model\Binary;
use Liip\ImagineBundle\Imagine\Filter\PostProcessor\PostProcessorInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageController extends FOSRestController
{
    /**
     * @Post("/images")
     */
    public function uploadAction(Request $request)
    {
        $imageManager = $this->container->get(ImageManager::class);
        $file = $request->files->get('image');
        $imageManager->upload($file);
    }

    /**
     * @Get("/images/{id}")
     */
    public function downloadAction(Request $request)
    {
        $imageManager = $this->container->get(ImageManager::class);
        $id = $request->get('id');
        $route = $this->getParameter('images_directory');

        $download = $imageManager->download($route, $id);

        if ($download == false) {
            return new Response('FALSE');
        }

        return $download;
    }

    /**
     * @Delete("/images/{id}")
     */
    public function deleteAction(Request $request)
    {
        $imageManager = $this->container->get(ImageManager::class);
        $id = $request->get('id');

        $delete = $imageManager->delete($route, $id);

        if ($delete == true) {
            return new RESPONSE('TRUE');
        }

        return new Response('FALSE');
    }
}
