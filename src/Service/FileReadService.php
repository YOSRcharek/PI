<?php
namespace App\Service;


class FileReadService
{
    public function readfile($filePath)
    {
        if (is_resource($filePath)) {
            // Si $filePath est une ressource, lisez son contenu
            $content = stream_get_contents($filePath);
            fclose($filePath); // Assurez-vous de fermer la ressource après lecture
            return $content;
        } else {
            // Si $filePath est une chaîne, utilisez file_get_contents normalement
            return file_get_contents($filePath);
        }
    }
}
