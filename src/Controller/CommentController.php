<?php

namespace App\Controller;

use App\Entity\{Comment, Course};
use App\Form\CommentType;
use App\Repository\CommentRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/new/{id}", name="comment_new", methods={"GET","POST"})
     */
    public function new(CommentRepository $commentRepository, Course $course, Security $security, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($security->getUser());
            $comment->setCreatedAt(new DateTime());
            $comment->setCourse($course);
            $comment->setParent($commentRepository->find($request->request->get("parentComment")));


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            $comment->setAuthor($comment->getAuthor()->getUsername());
            $comment->setCourse($course->getId());
            $comment->setParent(null);

            return $this->json(["type" => "new", $comment]);
        }

        return $this->json('error');
    }

    /**
     * @Route("/{id}/edit", name="comment_edit", methods={"GET","POST"})
     */
    public function edit(int $id, Request $request): Response
    {
        $commentManager = $this->getDoctrine()->getManager();
        $comment = $commentManager->getRepository(Comment::class)->find($id);


        $comment->setContent($request->request->get("comment")["content"]);
        $commentManager->flush();


        return $this->json(["type" => "edit", ["id" => $comment->getId(), "content" => $comment->getContent()]]);
    }

    /**
     * @Route("/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index');
    }
}
