<?php

namespace App\Tests\Manager;

use PHPUnit\Framework\TestCase;
use App\Manager\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ImageManagerTest extends TestCase
{
    public function testUpload()
    {
        $route = 'public/uploads/images/';
        $id = 'f3e59fa5067395b05eae6faf97138f8e.jpeg';

        $this->assertFileExists($route.$id);
    }

    public function testDownload()
    {
        $imageManager = new ImageManager();

        $route = 'tests/Manager/';
        $id = 'f3e59fa5067395b05eae6faf97138f8e.jpeg';

        $result = $imageManager->download($route, $id);
        $this->assertInstanceOf(BinaryFileResponse::class, $result);
    }

    public function testDelete()
    {
        $route = 'public/uploads/images/';
        $id = 'f3e59fa5067395b05eae6faf97138f8e.jpeg';

        $this->assertFileExists($route.$id);
    }
}