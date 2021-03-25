<?php

namespace App\Controller;

use App\Repository\{CoursePlaceRepository, CourseRepository, UserPlacesRepository};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\UserType;
use App\Entity\{Place, User, Character, UserCourses, UserPlaces};
use App\Security\AppAuthenticator;

class UserController extends AbstractController
{
    /**
     * @Route("/inscription", name="user_new")
     */
    public function signIn(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        GuardAuthenticatorHandler $guardHandler,
        AppAuthenticator $authenticator
    ): Response
    {
        $user = new user();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);
            $user->setRole('ROLE_USER');

            if ($user->getIsPlayer()) {
                $character = new Character();
                $gender = $request->request->get('gender');

                $character->setUser($user)
                          ->setTutorialDone(false)
                          ->setXp(0)
                          ->setStuck(0)
                          ->setGender($gender);

                $manager->persist($character);
            }

            $manager->persist($user);
            $manager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="user_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('user/login.html.twig', [
            'username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/deconnexion", name="user_logout")
     */
    public function logout()
    {
        // This function is managed by the Symfony Security Component
    }

    /**
     * @Route("/check_place/{id}", name="check_place", methods={"POST"})
     */
    public function checkPlace(
        Place $place,
        Security $security,
        EntityManagerInterface $manager,
        UserPlacesRepository $userPlacesRepository,
        CoursePlaceRepository $coursePlaceRepository,
        CourseRepository $courseRepository
    ): Response
    {
        $userPlace = (new UserPlaces())
            ->setInRealLife(true)
            ->setPlace($place)
            ->setUser($security->getUser());

        $manager->persist($userPlace);
        $manager->flush();

        $message = ['Vous venez de valider <span class="popup__title">'.$place->getName().'</span>!'];

        $checkedPlaces = $userPlacesRepository->findUser($security->getUser());
        $coursesOfPlace = $coursePlaceRepository->findPlaces($place);

        foreach ($coursesOfPlace as $id => $course) {
            $courseChecked = true;
            foreach ($course as $place) {
                if (!in_array($place, $checkedPlaces)) {
                    $courseChecked = false;
                    break;
                }
            }
            if ($courseChecked) {
                $course = $courseRepository->find($id);
                $userCourse = (new UserCourses())
                    ->setUser($security->getUser())
                    ->setCourse($course)
                    ->setInRealLife(true);

                $manager->persist($userCourse);
                $manager->flush();

                $message[] = 'Bravo ! Vous venez de valider le parcours <span class="popup__title">'.
                    $course->getName().'</span>!';
            }
        }

        return $this->json([
            'message' => $message,
            'code' => 200
        ]);
    }
}
