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
        return $this->render("session/session.html.twig", ['code' => $code, 'status' => $sessionStatus]);
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
            $nextOrder = $sessionQuestion->getSortorder() + 1;
            $nextQuestion = null;
            foreach($courseQuestions as $courseQuestion) {
                if($courseQuestion->getSortorder() == $nextOrder) {
                    $nextQuestion = $courseQuestion;
                    break;
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
        $countFrageAnswered = 0;
        $courseSession = $entityManager->getRepository(Session::class)->find($sessionId);
        if(isset($courseSession)){
            $activeQuestion = $courseSession->getFrage();
            $countFrageAnswered = count($activeQuestion->getSessionMemberFrages());
        }

        return new JsonResponse(["frageAnswered" => $countFrageAnswered]);
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