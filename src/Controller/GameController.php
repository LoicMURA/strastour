<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GameController extends AbstractController
{
    /**
     * @Route("/jeu", name="game_index")
     * @throws ExceptionInterface
     */
    public function index(
        Request $request,
        UserRepository $userRepository,
        NormalizerInterface $normalizer
    ): Response
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $user = $normalizer->normalize($user, 'json', ['groups' => 'user_game']);
        $id = $request->request->get("course") ?? 0;
        return $this->render('game/index.html.twig', ['user' => $user, 'course' => $id]);
    }

    /**
     * @Route("/jeu/{id}", name="game_getPlace")
     */
    public function getPlace(Course $course): Response
    {
        return $this->json($course, 200, [], ['groups' => 'course:show']);
    }
}
