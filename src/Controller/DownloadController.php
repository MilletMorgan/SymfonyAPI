<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Entity\Image;
use App\Form\DownloadType;

class DownloadController extends Controller
{
    public function download(Request $request)
    {
        $file= new Image();
        $image = $_GET['image'];
        $route = "uploads/images/";

        $form = $this->createForm(DownloadType::class, $file);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $response = new BinaryFileResponse($route . $image);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
            
            return $response;
        }
        
        return $this->render('Files/download.html.twig', array(
            'form' => $form->CreateView(),
        ));
    }
}
