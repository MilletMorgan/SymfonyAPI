<?php

namespace App\Tests\Manager;

use PHPUnit\Framework\TestCase;
use App\Manager\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageManagerTest extends TestCase
{
    public function testMoveUploadedFile()
    {
        $targetDir = 'tests/Manager/';

        $imageManager = new ImageManager($targetDir);

        $uploadedFileMock = $this->createMock(UploadedFile::class);

        $uploadedFileMock->method('guessExtension')->willReturn('.jpg');

        $resultFileMock = $uploadedFileMock->guessExtension();

        $result = $imageManager->moveUploadedFile($uploadedFileMock);

        $this->assertTrue($result);
    }

    public function testDownloadExists()
    {
        $route = 'tests/Manager/';

        $imageManager = new ImageManager($route);

        $id = 'f3e59fa5067395b05eae6faf97138f8e.jpeg';

        $result = $imageManager->download($id);

        $this->assertInstanceOf(BinaryFileResponse::class, $result);
    }

    public function testDownloadMissing()
    {
        $route = '/tests/Manager/';

        $imageManager = new ImageManager($route);

        $id = 'TEST.toto';

        $result = $imageManager->download($id);

        $this->assertFalse($result);
    }

    public function testDelete()
    {
        $route = '/tests/Manager/';

        $imageManager = new ImageManager($route);

        $id = 'TEST.toto';

        $result = $imageManager->delete($id);

        $this->assertFalse($result);
    }
}
