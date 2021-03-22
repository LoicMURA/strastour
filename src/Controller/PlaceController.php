<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/place")
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

            return $this->redirectToRoute('course_index');
        }

        return $this->render('place/new.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="place_show", methods={"GET"})
     */
    public function show(Place $place): Response
    {
        return $this->render('place/show.html.twig', [
            'place' => $place,
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

            return $this->redirectToRoute('course_index');
        }

        return $this->render('place/edit.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="place_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Place $place, FileService $file): Response
    {
        if ($this->isCsrfTokenValid('delete'.$place->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $file->removeFile($place, $this->getParameter('upload_directory'));
            $entityManager->remove($place);
            $entityManager->flush();
        }

        return $this->redirectToRoute('course_index');
    }
}