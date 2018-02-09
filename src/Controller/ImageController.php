<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\UploadType;
use App\Form\DownloadType;
use App\Form\DeleteType;
use App\Service\FileUploader;

use Doctrine\ORM\ORMException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $image = new Image();
        $form = $this->createForm(UploadType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush($image);

            $file = $image->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            return $this->redirect($this->generateUrl('download', array('image' => $fileName)));
        }

        return $this->render('Files/upload.html.twig', array(
            'image' => $image,
            'form' => $form->CreateView(),
        ));
    }

    public function download(Request $request)
    {
        $file = new Image();

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
            'image' => $image,
            'form' => $form->CreateView(),
        ));
    }

// Il faut toujours mettre "Action" à la fin du nom d'une méthode publique de controlleur
// On ajoute des "annotations" (le @Delete, il y a aussi des @Post, @Get etc) et on lui dit qu'on veut un id en paramètre
    /**
     * @Delete("/images/{id}")
     */
    public function deleteAction(int $id, Request $request)
    {
        // tu peux récupérer ton id en paramètre si il a le même nom que dans la route (ici {id} = $id)
        // tu peux aussi le récup dans $request->get('id')

        // Du coup maintenant tu peux supprimer l'image directement avec son ID, et tu retourne simplement true ou false pour dire si ça a réussi ou échec

        // ... je te laisse coder

//        $delete = new Image();
//
//        $form = $this->createForm(DeleteType::class, $delete);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted()) {
//            $files = glob('uploads/images/*'); // get all file names
//            foreach ($files as $file) { // iterate files
//                if (is_file($file)) {
//                    unlink($file);
//                } // delete file
//            }
//
//            return $this->redirectToRoute('upload');
//        }


// Plus besoin de twig ;)

//        return $this->render('Files/delete.html.twig', array(
//            'form' => $form->CreateView()
//        ));
    }
}
