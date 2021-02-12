<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Kurs;

class CourseSelection extends AbstractController
{

    /**
     * @Route("/course", name="course")
     */
    public function course(EntityManagerInterface $entityManager): Response
    {
        $courses = $entityManager->getRepository(Kurs::class)->findAll();

        return $this->render("courses/courseselection.html.twig", ['courses' => $courses]);
    }

    /**
     * @Route("/course/add", name="addcourse")
     */
    public function addCourse(Request $request, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager) : JsonResponse
    {
        $courses = $entityManager->getRepository(Kurs::class);
        $user = $tokenStorage->getToken()->getUser();

        $course = new Kurs();
        $course->setName($request->getContent());
        $course->setUser($user);

        $entityManager->persist($course);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Course added successfully', 'id' => $course->getId()], 200);
    }
    

    /**
     * @Route("/course/edit/{courseID}", name="editcourse")
     */
    public function editCourse(Request $request, $courseID, EntityManagerInterface $entityManager) : JsonResponse
    {
        $course = $entityManager->getRepository(Kurs::class)->find($courseID);
        if (!$course) {
            throw $this->createNotFoundException('No Course found.');
        }

        $course->setName($request->getContent());
        $entityManager->flush();

        return new JsonResponse(['message' => 'Course edited successfully.'], 200);
    }

    /**
     * @Route("/course/delete/{id}", name="deletecourse")
     */
    public function deleteCourse($id, EntityManagerInterface $entityManager) : JsonResponse
    {
        $course = $entityManager->getRepository(Kurs::class)->find($id);

        if (!$course) {
            throw $this->createNotFoundException('No Course found.');
        }

        $entityManager->remove($course);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Course deleted successfully.'], 200);
    }

}
