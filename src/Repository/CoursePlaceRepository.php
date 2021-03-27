<?php

namespace App\Repository;

use App\Entity\{Course, CoursePlace, Place};
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CoursePlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoursePlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoursePlace[]    findAll()
 * @method CoursePlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursePlaceRepository extends ServiceEntityRepository
{
    private $courseRepository;

    public function __construct(ManagerRegistry $registry, CourseRepository $courseRepository)
    {
        parent::__construct($registry, CoursePlace::class);
        $this->courseRepository = $courseRepository;
    }

    public function findCourse(Place $place): int
    {
        return ($this->findOneBy(['place' => $place]))->getCourse()->getId();
    }

//    public function findCourses(Place $place)
//    {
//        $manager = $this->getEntityManager();
//
//        $queryBuilder = ($manager->createQueryBuilder())
//            ->select('c.id')
//            ->from('App\Entity\CoursePlace', 't')
//            ->join('t.course', 'c')
//            ->andWhere('t.place = :place')
//            ->setParameter('place', $place);
//
//        return $queryBuilder->getQuery()->getResult();
//    }

    public function findPlaces($place): array
    {
        if ($place instanceof Course) $course = $place->getId();
        else $course = $this->findCourse($place);

        $manager = $this->getEntityManager();

        $queryBuilder = ($manager->createQueryBuilder())
            ->select('p.id', 'p.name')
            ->from('App\Entity\CoursePlace', 't')
            ->join('t.place', 'p')
            ->andWhere('t.course = :course')
            ->setParameter('course', $course);

        $result = $queryBuilder->getQuery()->getResult();

        $places = [];
        foreach ($result as $pl) {
            $places[$course][$pl['id']] = $pl['name'];
        }

        return $places;
    }

    private function findSibling($course, Place $place, string $type): ?Place
    {
        $position = ($this->findOneBy([
            'course' => $course,
            'place' => $place
        ]))->getPosition();

        $position = ($type == 'next') ? $position + 1 : $position - 1;

        if ($position >= 0) {
            $coursePlace = $this->findOneBy([
                'course' => $course,
                'position' => $position
            ]);
            if ($coursePlace) return $coursePlace->getPlace();
        }

        return null;
    }

    public function findNext($course, Place $place): ?Place
    {
        return $this->findSibling($course, $place, 'next');
    }

    public function findPrevious($course, Place $place): ?Place
    {
        return $this->findSibling($course, $place, 'previous');
    }

}
