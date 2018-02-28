<?php

namespace App\Tests\Manager;

use PHPUnit\Framework\TestCase;

class ImageManagerTest extends TestCase
{
    public function uploadTest()
    {
        $route = 'public/uploads/images/';
        $id = 'e849b20054dac0780ad60ee14198008e.jpeg';

        $this->assertFileExists($route.$id);
    }

    public function testDownload()
    {
        $route = 'public/uploads/images/';
        $id = 'e849b20054dac0780ad60ee14198008e.jpeg';

        $this->assertFileExists($route.$id);
    }

    public function testDelete()
    {
        $route = 'public/uploads/images/';
        $id = 'e849b20054dac0780ad60ee14198008e.jpeg';

        $this->assertFileExists($route.$id);
    }
}