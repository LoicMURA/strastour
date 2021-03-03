<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Place;
use App\Repository\PlaceRepository;

class PlaceController extends AbstractController
{
    /**
     * @Route("/lieu/{id}", name="place_show", requirements={"id":"\d+"})
     */
    public function show(Place $place): Response
    {
        return $this->render('place/show.html.twig', [
            'place' => $place
        ]);
    }
}
