<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PictureService extends AbstractController
{
    public function add($form, $entity): string
    {
        //on recupere la photo image
        $file = $form->get('photoImage')->getData();
        //on cree un nom unique 
        $filename = ($entity->getName() . uniqid() . ".webp");
        //on recupere le chemin du des dossiers de destination
        $path = $this->getParameter('images_directory');
        $path_thumb = $this->getParameter('thumb_images_directory');
        $path_fixed = $this->getParameter('fixed_images_directory');
        //on deplace le fichier sous le nouvau nom
        $file->move($path, $filename);
        //on supprime les anciens images si existe
        if ($entity->getPhoto()) {
            if (file_exists($path . $entity->getPhoto())) {
                unlink($path . $entity->getPhoto());
            }
            if (file_exists($path_thumb . $entity->getPhoto())) {
                unlink($path_thumb . $entity->getPhoto());
            }
            if (file_exists($path_fixed . $entity->getPhoto())) {
                unlink($path_fixed . $entity->getPhoto());
            }
        }

        return $filename;
    }
}
