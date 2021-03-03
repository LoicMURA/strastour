<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    /**
     * @Route("/", name="course_index")
     */
    public function index(CourseRepository $repository): Response
    {
        $courses = $repository->findAll();

        return $this->render('course/index.html.twig', [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/parcours/{id}", name="course_show", requirements={"id"="\d+"})
     */
    public function show(Course $course): Response
    {
        return $this->render('course/show.html.twig', [
            'course' => $course
        ]);
    }
}
