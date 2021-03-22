<?php

namespace App\Service;


class FileService
{
    private $uploadDir;

    public function __construct()
    {
        $this->uploadDir = '%kernel.project_dir%/public/assets/image';
    }

    public function uploadFile($product)
    {
        $file = $product->getPicture();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->uploadDir, $fileName);
        $product->setPicture($fileName);
    }

    public function removeFile($product): bool
    {
        if (is_string($product)) {
            $picture = $product;
        } else {
            $picture = $product->getPicture();
        }
        if (preg_match('/http/', $picture)) return true;
        $fileName = $this->uploadDir.'/'.$picture;

        if (file_exists($fileName)) return unlink($fileName);
        return false;
    }
}