<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\DownloadType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class DownloadController extends Controller
{
    public function download(Request $request)
    {
        $file= new Image();
        $fs = new Filesystem();

        $image = $_GET['image'];
        $route = "uploads/images/";

        $form = $this->createForm(DownloadType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = new BinaryFileResponse($route . $image);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
            
            return $response;
        }

        try {
            $fs->remove(array($route . $image));
        } catch(IOExceptionInterface $e) {
            echo "An error occurred while deleting your image at".$e->getPath();
        }

        return $this->render('Files/download.html.twig', array(
            'image' => $image,
            'form' => $form->CreateView(),
        ));
    }
}
