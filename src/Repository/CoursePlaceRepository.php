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

    // Return the id of the place's course
    public function findCourse(Place $place): int
    {
        return ($this->findOneBy(['place' => $place]))->getCourse()->getId();
    }

    // Return the id and name of all the places that are in the same(s) course
    // than the place or course given
    public function findPlaces($entity): array
    {
        if ($entity instanceof Course) $course = $entity->getId();
        else $course = $this->findCourse($entity);

        $manager = $this->getEntityManager();

        $queryBuilder = ($manager->createQueryBuilder())
            ->select('p.id', 'p.name')
            ->from('App\Entity\CoursePlace', 't')
            ->join('t.place', 'p')
            ->andWhere('t.course = :course')
            ->setParameter('course', $course);

        $result = $queryBuilder->getQuery()->getResult();

        $places = [];
        foreach ($result as $place) {
            $places[$course][$place['id']] = $place['name'];
        }

        return $places;
    }

    // Return the sibling of a place in a course
    private function findSibling($course, Place $place, string $type): ?Place
    {
        $currentPosition = ($this->findOneBy([
            'course' => $course,
            'place' => $place
        ]))->getPosition();

        $position = ($type == 'next') ? $currentPosition + 1 : $currentPosition - 1;

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
