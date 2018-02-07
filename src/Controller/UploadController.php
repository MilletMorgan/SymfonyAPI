<?php
// src/Controller/ProductController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Image;
use App\Form\ImageType;

class UploadController extends Controller
{
    public function upload(Request $request)
    {    
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush($image);
        }
            return $this->render('Files/upload.html.twig',array(
                'image' => $image,
                'form' => $form->CreateView(),
            ));   
        
    }
}