<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Attribute\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/fetch', name: 'fetch')]
    public function listStudents(StudentRepository $repo): Response
    {
        $result=$repo->findAll();
        return $this->render('student/list.html.twig', [
            'students' => $result,
        ]);
    }

    #[Route('/fetch2', name: 'fetch2')]
    public function listStudents2(ManagerRegistry $mr): Response
    {
        $repo=$mr->getRepository(Student::class);
        $result=$repo->findAll();
        return $this->render('student/list.html.twig', [
            'students' => $result,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(ManagerRegistry $mr, ClassroomRepository $repo, Request $req): Response
    {
        $s=new Student() ;  // creer une instance de l'objet etudiant
        $form= $this->createForm(StudentType::class, $s); // creer un formulaire
        $form->handleRequest($req); //Analyse de la requete et recuperer les donnees du formulaire

       if ($form->isSubmitted()){
        $em = $mr->getManager();
        $em->persist($s)  ;
        $em->flush($s);
        return $this->redirectToRoute('fetch2');

       }

        return $this->render('student/add.html.twig', [
            'f' => $form,
        ]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(StudentRepository $repo , $id, ManagerRegistry $mr): Response
    {
        $s=$repo->find($id);
        $em = $mr->getManager();
        $em->remove($s)  ;
        $em->flush($s);
        return $this->redirectToRoute('fetch2');
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(StudentRepository $repo , $id, ManagerRegistry $mr): Response
    {
        $s=$repo->find($id);
        $s->setName('update');

        $em = $mr->getManager();
        $em->persist($s)  ;
        $em->flush($s);
        return $this->redirectToRoute('fetch2');
    }

    #[Route('/dql', name: 'dql')]
    public function fetchStudentsDql(EntityManagerInterface $em,Request $request,StudentRepository $repo): Response
    {

        $result=$repo->findAll();

        if ($request->isMethod('post')){
            $value=$request->get('nom');
            $result=$repo->fetchStudentsByName($value);
          
        }
      
        return $this->render('student/dql.html.twig', [
            'students' =>  $result,
        ]);
    }
    
    #[Route('/dql2', name: 'dql2')]
    public function dql2(EntityManagerInterface $em,StudentRepository $repo): Response
    {
        $result=$repo->fetchStudentByAge();
        return $this->render('student/dql.html.twig', [
            'students' =>  $result,
        ]);
    }

    #[Route('/dql3', name: 'dql3')]
    public function dql3(EntityManagerInterface $em): Response
    {
        $req=$em->createQuery('select s.name from App\Entity\Student s order by s.name DESC');
        // select * from student
        $result=$req->getResult();
        dd($result);
    }

    #[Route('/dql4', name: 'dql4')]
    public function dqlJoin(StudentRepository $repo): Response
    {
        $result =$repo->join();
        dd($result);
    }

    #[Route('/qb', name: 'qb')]
    public function qb(StudentRepository $repo): Response
    {
        $result =$repo->fetchQB();
        dd($result);
    }
}
