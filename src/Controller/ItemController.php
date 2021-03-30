<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/item")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/new", name="item_new", methods={"GET","POST"})
     */
    public function new(
        Request $request,
        EntityManagerInterface $manager,
        FileService $fileService
    ): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileService->uploadFile($item, $this->getParameter('upload_directory'));

            $manager->persist($item);
            $manager->flush();

            return $this->redirectToRoute('course_index');
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods={"GET","POST"})
     */
    public function edit(
        Request $request,
        Item $item,
        FileService $fileService
    ): Response
    {
        $oldFile = $item->getPicture();

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($item->getPicture() === null) $item->setPicture($oldFile);
            else {
                $dir = $this->getParameter('upload_directory');

                $fileService->removeFile($oldFile, $dir);
                $fileService->uploadFile($item, $dir);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('course_index');
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_delete", methods={"DELETE"})
     */
    public function delete(
        Request $request,
        Item $item,
        EntityManagerInterface $manager,
        FileService $fileService
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $fileService->removeFile($item, $this->getParameter('upload_directory'));
            $manager->remove($item);
            $manager->flush();
        }

        return $this->redirectToRoute('course_index');
    }
}
