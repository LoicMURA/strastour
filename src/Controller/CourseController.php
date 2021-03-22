<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Service\FileService;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
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

    /**
     * @Route("/parcours/new", name="course_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileService $file): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $file->uploadFile($course, $this->getParameter('upload_directory'));
            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/parcours/{id}/edit", name="course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Course $course, FileService $file): Response
    {
        $oldFile = $course->getPicture();

        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($course->getPicture() == null) {
                $course->setPicture($oldFile);
            } else {
                $dir = $this->getParameter('upload_directory');

                $file->removeFile($oldFile, $dir);
                $file->uploadFile($course, $dir);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/parcours/{id}", name="course_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Course $course, FileService $file): Response
    {
        if ($this->isCsrfTokenValid('delete' . $course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $file->removeFile($course, $this->getParameter('upload_directory'));
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('course_index');
    }
}