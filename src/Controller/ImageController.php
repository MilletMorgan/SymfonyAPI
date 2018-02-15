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
    // /**
    //  * @Route("/")
    //  */
    // public function indexAction()
    // {
    //     return $this->render('base.html.twig');
    // }

    /**
     * @Post("/images")
     */
    public function uploadAction(Request $request)
    {
        $image = new Image();
        $form = $this->createForm(UploadType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush($image);

            $file = $image->getImage();
            $id = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),
                $id
            );

            return $this->redirect($this->generateUrl('download', array('id' => $id)));
        }

        return $this->render('Files/upload.html.twig', array(
            'image' => $image,
            'form' => $form->CreateView(),
        ));
    }

    /**
     * @Get("/images/{id}")
     */
    public function downloadAction(Request $request)
    {
        $file = new Image();

        $route = "uploads/images/";
        $id = $request->get('id');
        $id = strval(str_replace("id=", "", $id));

        $form = $this->createForm(DownloadType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = new BinaryFileResponse($route . $id);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
            
            return $response;
        }

        return $this->render('Files/download.html.twig', array(
            'image' => $id,
            'form' => $form->CreateView(),
        ));
    }

    /**
     * @Delete("/images/{id}")
     */
    public function deleteAction(Request $request)
    {
        $route = "uploads/images/";
        $id = $request->get('id');
        $id = strval(str_replace("id=", "", $id));
  
        if (is_file($route . $id)) {
            unlink($route . $id);

            return new Response('TRUE');
        } else
            return new Response('FALSE');
        
        return new Response('FALSE');

    //     $delete = new Image();

    //     $route = "uploads/images/";
    //     $id = $_GET['id'];

    //     $form = $this->createForm(DeleteType::class, $delete);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted()) {
    //         $files = glob($route . $id);
            
    //         foreach ($files as $file) {
    //             if (is_file($file)) {
    //                 unlink($file);
    //             }
    //         }

    //         return $this->redirectToRoute('upload');
    //     }

    //     return $this->render('Files/delete.html.twig', array(
    //         'form' => $form->CreateView()
    //     ));
    }
}
