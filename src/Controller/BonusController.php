<?php

namespace App\Controller;

use App\Entity\Bonus;
use App\Form\BonusType;
use App\Repository\BonusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bonus")
 */
class BonusController extends AbstractController
{
    /**
     * @Route("/new", name="bonus_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $bonus = new Bonus();
        $form = $this->createForm(BonusType::class, $bonus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($bonus);
            $manager->flush();

            return $this->redirectToRoute('course_index');
        }

        return $this->render('bonus/new.html.twig', [
            'bonus' => $bonus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bonus_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bonus $bonus): Response
    {
        $form = $this->createForm(BonusType::class, $bonus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('course_index');
        }

        return $this->render('bonus/edit.html.twig', [
            'bonus' => $bonus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bonus_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bonus $bonus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bonus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bonus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('course_index');
    }
}
