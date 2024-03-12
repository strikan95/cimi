<?php

namespace App\Shared\Service\FileUploader;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryUploader implements FileUploaderInterface
{
    private Cloudinary $instance;

    public function __construct()
    {
        $this->instance = new Cloudinary($this->getConfig());
    }

    private function getConfig(): Configuration
    {
        $config = new Configuration();
        $config->cloud->cloudName = 'daxjoycqb';
        $config->cloud->apiKey = '194933595854817';
        $config->cloud->apiSecret = 'rqgktZvC5f_ghYd5KKt7cSNzj3E';
        $config->url->secure = false;
        return $config;
    }

    public function getUploader(): Cloudinary
    {
        return $this->instance;
    }
}
