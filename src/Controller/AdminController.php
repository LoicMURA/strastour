<?php

namespace App\Controller;

use App\Repository\{CourseRepository, PlaceRepository};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index(PlaceRepository $placeRepository, CourseRepository $courseRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'places' => $placeRepository->findBy([], ['name' => 'ASC']),
            'courses' => $courseRepository->findBy([], ['name' => 'ASC'])
        ]);
    }
}
