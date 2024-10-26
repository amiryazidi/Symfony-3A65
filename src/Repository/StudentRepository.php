<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

//    /**
//     * @return Student[] Returns an array of Student objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Student
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

     public function fetchStudentsByName($name){
        $em=$this->getEntityManager();
        $req=$em->createQuery("select s from App\Entity\Student s where s.name =:n");
        $req->setParameter('n', $name);
        $result=$req->getResult();
        return $result;
     }

     public function fetchStudentByAge(){
        $em=$this->getEntityManager();
        $req=$em->createQuery('select s from App\Entity\Student s where s.age>25');
        $result=$req->getResult();
        return $result;
     }

     public function join () {
        $em=$this->getEntityManager();
        $req=$em->createQuery("select s from App\Entity\Student 
        s join s.classroom c where c.name='3A64' order by s.name ASC");
        $result=$req->getResult();
        return $result;
     }

     public function fetchQB () {
        $req=$this->createQueryBuilder('s')
        ->select('s.name','c.name as classroom')
        ->join('s.classroom','c')
        ->where("c.name= '3A65'")
        ->orderBy('s.name','ASC');
        $preResult=$req->getQuery();
        $result=$preResult->getResult();
        return $result;
     }
}
