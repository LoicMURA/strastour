<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Place, Course};
use App\Form\PlaceType;
use App\Repository\{CommentRepository, CoursePlaceRepository, UserPlacesRepository};
use App\Service\{FileService, GoogleApi};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/lieu")
 */
class PlaceController extends AbstractController
{
    /**
     * @Route("/new", name="place_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileService $file): Response
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $file->uploadFile($place, $this->getParameter('upload_directory'));
            $entityManager->persist($place);
            $entityManager->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('place/new.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="place_show", methods={"GET"})
     */
    public function show(
        Place $place,
        GoogleApi $api,
        Security $security,
        UserPlacesRepository $repository,
        CoursePlaceRepository $coursePlaceRepository
    ): Response
    {
        $user = $security->getUser();
        $isPlayer = $user ? $user->getIsPlayer() : false;
        $checkedPlaces = $repository->findBy(['place' => $place, 'user' => $user]);
        $check = count($checkedPlaces) > 0;

        $course = $coursePlaceRepository->findCourse($place);

        $siblings = [];
        if ($next = $coursePlaceRepository->findNext($course, $place)) {
            $siblings['next'] = $next;
        }
        if ($previous = $coursePlaceRepository->findPrevious($course, $place)) {
            $siblings['previous'] = $previous;
        }

        return $this->render('place/show.html.twig', [
            'place' => $place,
            'courseId' => $course,
            'API_KEY' => $api->getKey(),
            'checkedPlace' => $check,
            'isPlayer' => $isPlayer,
            'siblings' => $siblings
        ]);
    }

    /**
     * @Route("/{id}/edit", name="place_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Place $place, FileService $file): Response
    {
        $oldFile = $place->getPicture();
        
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($place->getPicture() == null){
                $place->setPicture($oldFile);
            }else{
                $dir = $this->getParameter('upload_directory');

                $file->removeFile($oldFile, $dir);
                $file->uploadFile($place, $dir);
            }
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('place/edit.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="place_delete", methods={"DELETE"})
     */
    public function delete(
        Request $request,
        Place $place,
        FileService $file,
        EntityManagerInterface $manager,
        CoursePlaceRepository $coursePlaceRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$place->getId(), $request->request->get('_token'))) {
            $file->removeFile($place, $this->getParameter('upload_directory'));

            $coursePlaces = $coursePlaceRepository->findBy(['place' => $place]);
            foreach ($coursePlaces as $cp) $manager->remove($cp);

            $manager->remove($place);
            $manager->flush();
        }

        return $this->redirectToRoute('admin_index');
    }
}