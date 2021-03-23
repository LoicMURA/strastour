<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class GameController extends AbstractController
{
    /**
     * @Route("/jeu", name="game_index")
     */
    public function index(UserRepository $userRepository, SerializerInterface $serializer, NormalizerInterface $normalizer): Response
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $user = $normalizer->normalize($user, 'json', ['groups' => 'user:game']);
        return $this->render('game/index.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/jeu/{id}", name="game_show")
     */
    public function showPlace(Course $course): Response
    {
        return $this->json($course, 200, [], ['groups' => 'course:show']);
    }
}
