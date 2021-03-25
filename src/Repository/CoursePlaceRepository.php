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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoursePlace::class);
    }

    public function findCourses(Place $place)
    {
        $manager = $this->getEntityManager();

        $queryBuilder = ($manager->createQueryBuilder())
            ->select('c.id')
            ->from('App\Entity\CoursePlace', 't')
            ->join('t.course', 'c')
            ->andWhere('t.place = :place')
            ->setParameter('place', $place);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findPlaces(Place $place): array
    {
        $courses = $this->findCourses($place);
        $places = [];
        foreach ($courses as $course) {
            $manager = $this->getEntityManager();

            $queryBuilder = ($manager->createQueryBuilder())
                ->select('p.id')
                ->from('App\Entity\CoursePlace', 't')
                ->join('t.place', 'p')
                ->andWhere('t.course = :course')
                ->setParameter('course', $course['id']);

            $result = $queryBuilder->getQuery()->getResult();
            $place = [];
            foreach ($result as $pl) {
                array_push($place, $pl['id']);
            }
            $places[$course['id']] = $place;
        }

        return $places;
    }

    private function findSibling(Course $course, Place $place, string $type): ?Place
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

    public function findNext(Course $course, Place $place): ?Place
    {
        return $this->findSibling($course, $place, 'next');
    }

    public function findPrevious(Course $course, Place $place): ?Place
    {
        return $this->findSibling($course, $place, 'previous');
    }

}
