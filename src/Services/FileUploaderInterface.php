<?php

namespace App\Services;

use Cloudinary\Cloudinary;

interface FileUploaderInterface
{
    public function getUploader(): Cloudinary;
}