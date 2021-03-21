<?php

namespace App\DataFixtures;

use App\Repository\{BonusRepository,
    CommentRepository,
    CourseRepository,
    ItemRepository,
    PlaceRepository,
    UserRepository};
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\{ManagerRegistry, ObjectManager};
use App\Entity\{Bonus,
    Character,
    Comment,
    Course,
    Inventory,
    ItemBonuses,
    Place,
    CoursePlace,
    Item,
    Type,
    Theme,
    User,
    UserCourses,
    UserPlaces};
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $hasher;
    private $bonusRepository;
    private $itemRepository;
    private $userRepository;
    private $courseRepository;
    private $placeRepository;
    private $commentRepository;

    public function __construct(
        UserPasswordEncoderInterface $hasher,
        BonusRepository $bonusRepository,
        ItemRepository $itemRepository,
        UserRepository $userRepository,
        CourseRepository $courseRepository,
        PlaceRepository $placeRepository,
        CommentRepository $commentRepository
    )
    {
        $this->hasher = $hasher;
        $this->bonusRepository = $bonusRepository;
        $this->itemRepository = $itemRepository;
        $this->userRepository = $userRepository;
        $this->courseRepository = $courseRepository;
        $this->placeRepository = $placeRepository;
        $this->commentRepository = $commentRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < mt_rand(10,15); $i++) {
            $bonus = new Bonus();
            $bonus->setName($faker->words(3, true));

            $manager->persist($bonus);
        }
        $manager->flush();

        for ($i = 0; $i < 7; $i++) {
            $type = new Type();
            $type->setName($faker->words(5, true));

            $manager->persist($type);

            for ($j = 0; $j < mt_rand(2, 5); $j++) {
                $item = new Item();
                $item->setName($faker->words(2, true))
                     ->setDescription($faker->paragraph)
                     ->setPrice($faker->numberBetween(5, 100))
                     ->setType($type)
                     ->setPicture('https://picsum.photos/300/200?random='.mt_rand(0, 150000));

                $manager->persist($item);

                $bonuses = $this->bonusRepository->findAll();
                for ($k = 0; $k < 2; $k++) {
                    $itemBonus = new ItemBonuses();

                    $bonus = $faker->randomElement($bonuses);
                    unset($bonuses[array_search($bonus, $bonuses)]);

                    $itemBonus->setItem($item)
                              ->setBonus($bonus)
                              ->setValue($faker->numberBetween(1, 10));

                    $manager->persist($itemBonus);
                }
            }
        }
        $manager->flush();

        $items = $this->itemRepository->findAll();

        for ($i = 0; $i < 6; $i++) {
            $user = new User();
            $role = ($i === 0) ? "ROLE_ADMIN" : "ROLE_USER";

            $user->setRole($role)
                 ->setUsername($faker->name)
                 ->setPassword($this->hasher->encodePassword($user, 'password'))
                 ->setIsPlayer($faker->boolean);

            $manager->persist($user);

            if ($user->getIsPlayer()) {
                $character = new Character();
                $character->setUser($user)
                          ->setGender($faker->randomElement(['h', 'f']))
                          ->setStuck($faker->numberBetween(0, 250))
                          ->setXp($faker->numberBetween(0, 5000))
                          ->setTutorialDone($faker->boolean);

                $manager->persist($character);

                for ($j = 0; $j < mt_rand(2, 5); $j++) {
                    $inventory = new Inventory();
                    $inventory->setPlayer($character)
                              ->setQuality($faker->numberBetween(0, 100))
                              ->setQuantity($faker->numberBetween(1, 3))
                              ->setItem($faker->randomElement($items));

                    $manager->persist($inventory);
                }
            }
        }
        $manager->flush();

        for ($i = 0; $i < 5; $i++) {
            $theme = new Theme();
            $theme->setName($faker->words(3, true));

            $manager->persist($theme);

            for ($j = 0; $j < mt_rand(1, 2); $j++) {
                $course = new Course();
                $course->setName($faker->words(2, true));
                $course->setDescription($faker->paragraph);
                $course->setPicture('https://picsum.photos/300/200?random='.mt_rand(0, 150000));
                $course->setTheme($theme);
                $course->setItem($faker->randomElement($items));

                $manager->persist($course);

                for ($k = 0; $k < mt_rand(3, 7); $k++) {
                    $place = new Place();
                    $place->setName($faker->words(3, true));
                    $place->setAddress($faker->address);
                    $place->setDescription($faker->paragraph);
                    $place->setPicture('https://picsum.photos/300/200?random='.mt_rand(0, 150000));
                    $place->setLatitude($faker->latitude);
                    $place->setLongitude($faker->longitude);

                    $coursePlace = new CoursePlace();
                    $coursePlace->setCourse($course)
                                ->setPlace($place)
                                ->setPosition($k);

                    $manager->persist($place);
                    $manager->persist($coursePlace);
                }
            }
        }
        $manager->flush();

        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $courses = $this->courseRepository->findAll();
            for ($i = 0; $i < mt_rand(0, 5); $i++) {
                $userCourse = new UserCourses();

                $course = $faker->randomElement($courses);
                unset($courses[array_search($course, $courses)]);

                $userCourse->setCourse($course)
                           ->setUser($user)
                           ->setInRealLife($faker->boolean);

                $manager->persist($userCourse);
            }

            $places = $this->placeRepository->findAll();
            for ($i = 0; $i < mt_rand(3, 15); $i++) {
                $userPlace = new UserPlaces();

                $place = $faker->randomElement($places);
                unset($places[array_search($place, $places)]);

                $userPlace->setPlace($place)
                           ->setUser($user)
                           ->setInRealLife($faker->boolean);

                $manager->persist($userPlace);
            }

            $courses = $this->courseRepository->findAll();
            for ($i = 0; $i < mt_rand(0, 10); $i++) {
                $comment = new Comment();

                $comment->setCourse($faker->randomElement($courses))
                        ->setContent(implode($faker->paragraphs()))
                        ->setAuthor($user)
                        ->setCreatedAt($faker->dateTimeBetween('-60 days'));

                $comments = $this->commentRepository->findAll();
                if (count($comments) > 0 && mt_rand(0, 100) > 90) {
                    $comment->setParent($faker->randomElement($comments));
                }

                $manager->persist($comment);
                $manager->flush();
            }
        }
        $manager->flush();
    }
}
