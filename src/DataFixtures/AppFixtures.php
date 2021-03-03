<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\{ManagerRegistry, ObjectManager};
use App\Entity\{Course, Place, CoursePlace, Item, Type, Theme, User};
use App\Repository\ItemRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder as encoder;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        for ($i = 1; $i <= 5; $i++)
        {
            $type = new Type();
            $type->setName($faker->sentence(5));
            $theme = new Theme();
            $theme->setName($faker->sentence(5));

            $manager->persist($theme);
            $manager->persist($type);
            $manager->flush();

            for ($j = 1; $j <= 3; $j++)
            {
                $item = new Item();
                $item->setName($faker->sentence(2));
                $item->setDescription($faker->paragraph);
                $item->setPicture('https://picsum.photos/100/100');
                $item->setPrice($faker->numberBetween(2, 50));
                $item->setType($type);

                $manager->persist($item);
                $manager->flush();

                $course = new Course();
                $course->setName($faker->sentence(5));
                $course->setDescription($faker->paragraph);
                $course->setPicture('https://picsum.photos/200/300');
                $course->setTheme($theme);
                $course->setItem($item);

                $manager->persist($course);
                $manager->flush();

                for ($k = 1; $k <= mt_rand(5,8); $k++)
                {
                    $place = new Place();
                    $place->setName($faker->sentence(3));
                    $place->setAddress($faker->address);
                    $place->setDescription($faker->paragraph);
                    $place->setPicture('https://picsum.photos/200/300');
                    $place->setLatitude($faker->latitude);
                    $place->setLongitude($faker->longitude);

                    $manager->persist($place);
                    $manager->flush();

                    $router = new CoursePlace();
                    $router->setCourse($course);
                    $router->setPlace($place);
                    $router->setPosition($k);

                    $manager->persist($router);
                    $manager->flush();
                }
            }
        }

        // Mot de passe : AdminPsw1#
        $hash = '$2y$13$Qt2csGz8LkW1AQe3D6pTM.xddtE.aSM0H6eb3CmjwKPYGR/PBuKEe';

        $user = new User();
        $user->setUsername('administrator');
        $user->setIsPlayer(false);
        $user->setRole('ROLE_ADMIN');
        $user->setPassword($hash);

        $manager->persist($user);
        $manager->flush();
    }
}
