<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    
    public function index(){
        return $this->render('base.html.twig');
    }

    public function upload(){
        return $this->render('upload.html.twig');
    }

    public function download(){
        return $this->render('download.html.twig');
    }
}