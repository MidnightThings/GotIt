<?php

namespace App\Controller;

use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Kurs;
use App\Entity\Frage;


class CourseOverviewController extends AbstractController
{
    /**
     * @Route("/course/edit/{courseID}", name="courseOverview")
     */
    public function courseOverview($courseID, EntityManagerInterface $entityManager): Response
    {
        $course = $entityManager->getRepository(Kurs::class)->find($courseID);
        $questions = $course->getFrages();
        return $this->render("courses/courseoverview.html.twig", ['course' => $course, 'questions' => $questions]);
    }

    /**
     * @Route("/course/name/{courseID}", name="updateCourseName")
     */
    public function updateCourseName($courseID, Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = $entityManager->getRepository(Kurs::class)->find($courseID);
        $course->setName($request->getContent());
        
        $entityManager->persist($course);
        $entityManager->flush();
        $response = new Response();
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * @Route("/question/add/{courseID}", name="addQuestion")
     */
    public function addQuestion($courseID, EntityManagerInterface $entityManager): Response
    {
        $course = $entityManager->getRepository(Kurs::class)->find($courseID);
        $question = new Frage();

        if (!$course) {
            throw $this->createNotFoundException('No Course found.');
        }

        $question->setKurs($course);
        $question->setContent("");
        $question->setSortorder(0);
        $entityManager->persist($question);
        $entityManager->flush();
        $response = new Response();
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * @Route("/question/edit/{courseID}/{questionID}", name="editQuestion")
     */
    public function editQuestion($courseID, $questionID, EntityManagerInterface $entityManager, Request $request): Response
    {
        $question = $entityManager->getRepository(Frage::class)->find($questionID);
        $question->setContent($request->get('questionContent'));
        $question->setSortorder($request->get('sortOrderValue'));

        $entityManager->flush();
        $response = new Response();
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * @Route("/question/delete/{courseID}/{questionID}", name="deleteQuestion")
     */
    public function deleteQuestion($courseID, $questionID, EntityManagerInterface $entityManager): Response
    {
        $question = $entityManager->getRepository(Frage::class)->find($questionID);

        if (!$question) {
            throw $this->createNotFoundException('No Course found.');
        }

        $entityManager->remove($question);
        $entityManager->flush();
        $response = new Response();
        $response->setStatusCode(200);
        return $response;
    }
}