<?php

namespace App\Controller;

use App\Entity\{Course, Comment, CoursePlace, Place};
use App\Form\{CommentType, CourseType};
use App\Service\{FileService, GoogleApi};
use App\Repository\{CourseRepository,
    CommentRepository,
    CoursePlaceRepository,
    PlaceRepository,
    UserPlacesRepository};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
     * @Route("/parcours", name="course_showall")
     */
    public function showall(CourseRepository $repository): Response
    {
        $courses = $repository->findAll();

        return $this->render('course/showall.html.twig', [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/parcours/{id}", name="course_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function show(
        Course $course,
        GoogleApi $api,
        Request $request,
        CommentRepository $repository,
        UserPlacesRepository $userPlacesRepository,
        Security $security
    ): Response
    {
        $comments = $repository->findBy(['course' => $course]);

        $comments_by_id = [];
        foreach ($comments as $comment) {
            $comments_by_id[$comment->getId()] = $comment;
        }

        foreach ($comments as $k => $comment) {
            if ($comment->getParent() != null) {
                $comments_by_id[$comment->getParent()->getId()]->children[] = $comment;
                unset($comments[$k]);
            }
        }

        $checkedPlaces = ($user = $security->getUser()) ? $userPlacesRepository->findUser
    ($user) : '';

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        return $this->render('course/show.html.twig', [
            'course' => $course,
            'comments' => $comments,
            'form' => $form->createView(),
            'API_KEY' => $api->getKey(),
            'checkedPlaces' => $checkedPlaces
        ]);
    }

    /**
     * @Route("/parcours/new", name="course_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileService $file, EntityManagerInterface $manager): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file->uploadFile($course, $this->getParameter('upload_directory'));
            $manager->persist($course);
            $manager->flush();

            return $this->redirectToRoute('course_edit', ['id' => $course->getId()]);
        }

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/parcours/{id}/edit", name="course_edit", methods={"GET","POST"})
     */
    public function edit(
        Request $request,
        Course $course,
        FileService $file,
        PlaceRepository $placeRepository,
        EntityManagerInterface $manager,
        CoursePlaceRepository $coursePlaceRepository
    ): Response
    {
        $oldFile = $course->getPicture();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        $coursePlaces = $coursePlaceRepository->findPlaces($course);

        if ($form->isSubmitted() && $form->isValid()) {
            // update coursePlaces
            foreach ($coursePlaceRepository->findBy(['course' => $course]) as $coursePlace) {
                $manager->remove($coursePlace);
            }
            $manager->flush();


            $i = 1;
            while ($place = $request->request->get('place-'.$i)) {
                if ($place != 0) {
                    $coursePlace = new CoursePlace();
                    $coursePlace->setPlace($placeRepository->find($place))
                                ->setCourse($course)
                                ->setPosition($i);

                    $manager->persist($coursePlace);
                    $manager->flush();
                    $i++;
                }
            }

            // update picture
            if ($course->getPicture() == null) {
                $course->setPicture($oldFile);
            } else {
                $dir = $this->getParameter('upload_directory');

                $file->removeFile($oldFile, $dir);
                $file->uploadFile($course, $dir);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
            'freePlaces' => $placeRepository->findFreePlaces(),
            'coursePlaces' => $coursePlaces
        ]);
    }

    /**
     * @Route("/parcours/{id}", name="course_delete", methods={"DELETE"})
     */
    public function delete(
        Request $request,
        Course $course,
        FileService $file,
        EntityManagerInterface $manager,
        CoursePlaceRepository $coursePlaceRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $course->getId(), $request->request->get('_token'))) {
            $file->removeFile($course, $this->getParameter('upload_directory'));
            foreach ($coursePlaceRepository->findBy(['course' => $course]) as $cp)
                $manager->remove($cp);

            $manager->remove($course);
            $manager->flush();
        }

        return $this->redirectToRoute('admin_index');
    }
}
