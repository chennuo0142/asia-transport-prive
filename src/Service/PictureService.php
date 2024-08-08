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
        //on recupere le chemin du dosser de destination
        $path = $this->getParameter('images_directory');
        //on deplace le fichier sous le nouvau nom
        $file->move($path, $filename);

        return $filename;
    }
}
