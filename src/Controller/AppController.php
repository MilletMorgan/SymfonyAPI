<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    
    public function index(){
        return $this->render('index.html.twig');
    }
}