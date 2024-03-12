<?php

namespace App\Shared\Service\FileUploader;

use Cloudinary\Cloudinary;

interface FileUploaderInterface
{
    public function getUploader(): Cloudinary;
}
