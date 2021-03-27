<?php

namespace App\Service;


class FileService
{
    public function uploadFile($product, string $uploadDir)
    {
        $file = $product->getPicture();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($uploadDir, $fileName);
        $product->setPicture($fileName);
    }

    public function removeFile($product, string $uploadDir): bool
    {
        if (is_string($product)) {
            $picture = $product;
        } else {
            $picture = $product->getPicture();
        }

        if (preg_match('/http/', $picture)) return true;
        $fileName = $uploadDir.'/'.$picture;

        if (file_exists($fileName) && $picture != null) return unlink($fileName);
        return false;
    }
}