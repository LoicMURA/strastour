<?php

namespace App\Service;


class FileService
{
    public function uploadFile($object, string $uploadDir)
    {
        $file = $object->getPicture();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($uploadDir, $fileName);
        $object->setPicture($fileName);
    }

    public function removeFile($object, string $uploadDir): bool
    {
        if (is_string($object)) {
            $picture = $object;
        } else {
            $picture = $object->getPicture();
        }

        if (preg_match('/http/', $picture)) return true;
        $fileName = $uploadDir.'/'.$picture;

        if (file_exists($fileName) && $picture != null) return unlink($fileName);
        return false;
    }
}