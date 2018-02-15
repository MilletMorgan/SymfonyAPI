<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\UploadType;
use App\Form\DownloadType;
use App\Form\DeleteType;
use App\Service\FileUploader;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\Mapping\Annotation;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ImageController extends FOSRestController
{

    /**
     * @Post("/images")
     */
    public function uploadAction(Request $request)
    {
        $form = $this->createForm(UploadType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $image->getImage();
            $id = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),
                $id
            );

            return $this->redirect($this->generateUrl('download', array('id' => $id)));
        }
    }

    /**
     * @Get("/images/{id}")
     */
    public function downloadAction(Request $request)
    {
        $route = "uploads/images/";
        $id = $request->get('id');

        $response = new BinaryFileResponse($route . $id);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        
        return $response;
    }

    /**
     * @Delete("/images/{id}")
     */
    public function deleteAction(Request $request)
    {
        $route = "uploads/images/";
        $id = $request->get('id');
  
        if (is_file($route . $id)) {
            unlink($route . $id);

            return new Response('TRUE');
        }
        
        return new Response('FALSE');
    }
}
