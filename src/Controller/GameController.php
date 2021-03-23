<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class GameController extends AbstractController
{
    /**
     * @Route("/jeu", name="game_index")
     */
    public function index(): Response
    {
        return $this->render('game/index.html.twig');
    }

    /**
     * @Route("/jeu/{id}", name="game_show")
     */
    public function showPlace(Course $course): Response
    {
        return $this->json($course, 200, [], ["groups" => "course:show"]);
    }
}
