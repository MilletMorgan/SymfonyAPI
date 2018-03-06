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

class ImageController extends FOSRestController
{
    const EXECUTABLE_PATH = 'mozjpeg/cjpeg.exe';

    /**
     * @param BinaryInterface $binary
     *
     * @return BinaryInterface
     */
    public function process(BinaryInterface $binary)
    {
        if (!in_array(strtolower($binary->getMineType()), array('image/png'))) {
            return $binary;
        }
        
        if (false === $input = tempnam($path = sys_get_temp_dir(), 'custom')) {
            throw new \Exception(sprintf('Error created tmp file in "%s".', $path));
        }
        
        file_put_contents($input, $binary->getContent());

        $pb = new ProcessBuilder(array(self::EXECUTABLE_PATH));
        $pb->add($input);

        $process = $pb->getProcess();
        $process->run();

        if (0 !== $process->getExitCode()) {
            unlink($input);
            throw new ProcessFailedException($process);
        }

        $result = new Binary(
            file_get_contents($input),
            $binary->getMimeType(),
            $binary->getFormat()
        );

        unlink($input);

        return $result;
    }

    /**
     * @Post("/images")
     * @Route("/images")
     */
    public function uploadAction(Request $request)
    {
        $image = new Image;
        $imageManager = $this->container->get(ImageManager::class);

        $form = $this->createForm(UploadType::class, $image);
        $form->handleRequest($request);

        $id = $request->get('id');
        $route = $this->getParameter('images_directory');

        if ($form->isSubmitted() && $form->isValid()) {
             $file = $image->getImage();
             $id = md5(uniqid()).'.'.$file->guessExtension();
             $file->move($route,$id);

             return $this->redirect($this->generateUrl('download', array('id' => $id)));    
        }

        return $this->render('Files/upload.html.twig', array(
            'image' => $image,
            'form' => $form->CreateView(),
        ));
    }

    // /**
    //  * @Route("/images")
    //  * @Post("/images")
    //  */
    // public function uploadAction(Request $request)
    // {
    //     $image = new Image();
    //     $imageManager = $this->container->get(ImageManager::class);

    //     $form = $this->createForm(UploadType::class, $image);
    //     $form->handleRequest($request);

    //     $id = $request->get('id');
    //     $route = $this->getParameter('images_directory');

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $upload = $imageManager->upload($route, $id, $image);

    //         return $upload;
    //     }
    //     return $this->render('Files/upload.html.twig', array(
    //         'image' => $image,
    //         'form' => $form->CreateView(),
    //     ));
    // }    

    /**
     * @Get("/images/{id}")
     * @Route("/images/{id}")
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
