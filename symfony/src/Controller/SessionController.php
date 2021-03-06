<?php

namespace App\Controller;

use App\Entity\Kurs;
use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\CodeGenerator;

class SessionController extends AbstractController
{
    /**
     * @Route("/session/start/{courseid}", name="session_start")
     */
    public function startSession($courseid, EntityManagerInterface $entityManager, CodeGenerator $codeGenerator): Response
    {
        $course = $entityManager->getRepository(Kurs::class)->find($courseid);
        $code = $codeGenerator->generateRandomCode(6);
        $session = new Session();
        $session->setCode($code);
        $session->setStatus('IDLE');
        $session->setKurs($course);

        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirect("/session/" . $code);
    }

    /**
     * @Route("/session/{code}", name="session")
     */
    public function goToSession($code, EntityManagerInterface $entityManager): Response
    {
        $session = $entityManager->getRepository(Session::class)->findOneBy(['code' => $code]);
        $sessionStatus = $session->getStatus();
        $currentQuestion = $session->getFrage();
        $sessionQuestions = $session->getKurs()->getFrages();
        $maxQuestionCount = count($sessionQuestions);
        $answeredQuestionsCount = 0;
        if ($sessionStatus == 'FINISH'){
            $answeredQuestionsCount = $maxQuestionCount;
        } elseif (isset($currentQuestion)){
            $answeredQuestionsCount += 1;
            foreach ($sessionQuestions as $question){
                if ($question->getSortOrder() < $currentQuestion->getSortOrder()){
                    $answeredQuestionsCount += 1;
                }
            }
        }

        $answeredQuestions = $answeredQuestionsCount.'/'.$maxQuestionCount;
        return $this->render("session/session.html.twig", ['code' => $code, 'status' => $sessionStatus, 'sessionId' => $session->getId(), 'currentQuestion' => $currentQuestion, 'qCount' => $answeredQuestions]);
    }

    /**
     * @Route("/session/{code}/next", name="session_next")
     */
    public function nextStep($code, EntityManagerInterface $entityManager): Response
    {
        $session = $entityManager->getRepository(Session::class)->findOneBy(['code' => $code]);
        $sessionStatus = $session->getStatus();
        $sessionQuestion = $session->getFrage();
        $course = $session->getKurs();
        $courseQuestions = $course->getFrages();
        if(!isset($sessionQuestion)) {
            $sessionQuestion = $courseQuestions[0];
            $session->setFrage($sessionQuestion);
            $session->setStatus('QUESTION');
        } else if ($sessionStatus === 'IDLE') {
            $nextQuestion = null;
            $currentID = $sessionQuestion->getId();
            for ($i = 0; $i < count($courseQuestions); $i++){
                if ($courseQuestions[$i]->getId() == $currentID){
                    $nextQuestion = $courseQuestions[$i+1];
                }
            }
            if(isset($nextQuestion)) {
                $session->setFrage($nextQuestion);
                $session->setStatus('QUESTION');
            } else {
                $session->setStatus('FINISH');
                $session->setFrage(null);
            } 
        } else if ($sessionStatus === 'QUESTION') {
            $session->setStatus('RATE');
        } else if ($sessionStatus === 'RATE') {
            $session->setStatus('IDLE');
        }

        $entityManager->persist($session);
        $entityManager->flush();
        
        
        return $this->redirect("/session/" . $code);
    }

    /**
     * @Route("/session/members/{sessionId}", name="session_members", methods={"GET"}) 
     */
    public function getActiveSessionMembers($sessionId, EntityManagerInterface $entityManager):JsonResponse
    {
        $countActiveMembers = 0;
        $courseSession = $entityManager->getRepository(Session::class)->find($sessionId);
        if(isset($courseSession)){
            $members = $courseSession->getSessionMembers();
            $countActiveMembers = count($members);
        }

        return new JsonResponse(["sessionMembers" => $countActiveMembers]);
    }

    /**
     * @Route("/session/memberfrage/{sessionId}", name="session_members_frage", methods={"GET"})
     */
    public function getFrageAnsweredMembers($sessionId, EntityManagerInterface $entityManager):JsonResponse
    {
        $sessionRepository = $entityManager->getRepository(Session::class);
        $countFrageAnswered = $sessionRepository->getCountFrageAnswered($sessionId);

        return new JsonResponse(["frageAnswered" => $countFrageAnswered[0]["count"]]);
    }

    /**
     * @Route("/session/memberratings/{sessionId}", name="session_members_rating", methods={"GET"})
     */
    public function getRatingMembers($sessionId, EntityManagerInterface $entityManager):JsonResponse
    {
        $countRating = 0;
        $courseSession = $entityManager->getRepository(Session::class)->find($sessionId);
        if(isset($courseSession)){
            $countRating = $courseSession->getCountRatings();
        }

        return new JsonResponse(["ratingMembers" => $countRating]);

    }
}