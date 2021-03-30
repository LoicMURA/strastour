<?php

namespace App\Controller;

use App\Entity\{Comment, Course};
use App\Form\CommentType;
use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
    public function new(
        CommentRepository $commentRepository,
        Course $course,
        Security $security,
        Request $request,
        EntityManagerInterface $manager
    ): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($security->getUser());
            $comment->setCreatedAt(new DateTime());
            $comment->setCourse($course);
            $comment->setParent($commentRepository->find($request->request->get("parentComment")));

            $manager->persist($comment);
            $manager->flush();

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
    public function edit(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $comment = $manager->getRepository(Comment::class)->find($id);

        $comment->setContent($request->request->get("comment")["content"]);
        $manager->flush();

        return $this->json(["type" => "edit", ["id" => $comment->getId(), "content" => $comment->getContent()]]);
    }

    /**
     * @Route("/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $manager->remove($comment);
            $manager->flush();
        }

        return $this->redirectToRoute('comment_index');
    }
}
