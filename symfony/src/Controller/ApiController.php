<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Session;
use App\Entity\SessionMember;
use App\Entity\SessionMemberFrage;
use App\Entity\Frage;

class ApiController extends AbstractController
{
    /**
     * @Route("/coursesession/enter/{code}", name="coursesession_enter", methods={"GET"})
     */
    public function courseSessionEnter($code, EntityManagerInterface $entityManager):JsonResponse
    {
        
        $courseSession = $entityManager->getRepository(Session::class)->findOneBy(["code" => $code]);
        if($courseSession){
            $sessionMember = new SessionMember();
            $sessionMember->setSession($courseSession);
            $entityManager->persist($sessionMember);
            $entityManager->flush();

            return new JsonResponse(["token" => $sessionMember->getId()]);    
        }else{
            return new JsonResponse(["error" => "course not found!"], 500);
        }
    }

    /**
     * @Route("/coursesession/status/{token}", name="coursesession_status", methods={"GET"})
     */
    public function courseSessionStatus($token, EntityManagerInterface $entityManager):JsonResponse
    {

        /**
         * 1. check session status
         * 2. Build JSON
         * 3. return JSON
         */
        
        $sessionMember = $entityManager->getRepository(SessionMember::class)->find($token);
        $session = $sessionMember->getSession();
        $status = strtoupper($session->getStatus());

        $question = [
            "content" => "",
            "id" => ""
        ];

        $answers = [];

        if($status == "QUESTION"){
            $frage = $session->getFrage();
            $question = [
                "content" => $frage->getContent(),
                "id" => $frage->getId()
            ];
        }elseif($status == "RATE"){
            $answerObject = $sessionMember->getTmpRateAnswer();
            if(!count($answerObject)){
                $sessionRepository = $entityManager->getRepository(Session::class);
                $answerResult = $sessionRepository->getAnswers($token);
                shuffle($answerResult);
                $answerArray = array_slice($answerResult,0,3);
                foreach($answerArray as $answer){
                    $answerObject = $entityManager->getRepository(SessionMemberFrage::class)->find($answer["id"]);
                    $sessionMember->addTmpRateAnswer($answerObject);
                }
                $entityManager->persist($sessionMember);
                $entityManager->flush();
            }else{
                foreach($answerObject as $answer){
                    $answerArray[] = ["content" => $answer->getContent(), "id" => $answer->getId()];
                }
            }

            $answers = $answerArray;
        }
        $pollingArray = [
                "status" => $status,
                "question" => $question,
                "answers" => $answers
            ];

        return new JsonResponse($pollingArray);
    }

    /**
     * @Route("/coursesession/answer", name="coursesession_answer", methods={"POST"})
     */
    public function courseSessionAnswer(EntityManagerInterface $entityManager, Request $request):Response
    {

        $frage = $entityManager->getRepository(Frage::class)->find(($request->request->get("questionId")));
        if(!isset($frage)){
            return new Response();
        }
        $sessionMember =  $entityManager->getRepository(SessionMember::class)->find(($request->request->get("token")));
        if(!isset($sessionMember)){
            return new Response();
        }
        $sessionMemberFrage = new SessionMemberFrage();
        $sessionMemberFrage->setSessionmember($sessionMember);
        $sessionMemberFrage->setFrage($frage);
        $sessionMemberFrage->setContent($request->request->get("answer"));
        $sessionMemberFrage->setRating(0);
        $sessionMemberFrage->setRatingCount(0);

        $entityManager->persist($sessionMemberFrage);
        $entityManager->flush();
        
        return new Response();
    }

    /**
     * @Route("/coursesession/rate", name="coursesession_rating", methods={"POST"})
     */
    public function courseSessionRating(EntityManagerInterface $entityManager, Request $request):Response
    {
        foreach($request->request->get("ratings") as $rating){
            $sessionMemberFrage = $entityManager->getRepository(SessionMemberFrage::class)->find($rating["answerId"]);
            if(!isset($sessionMemberFrage)){
                return new Response();
            }
            if($rating["rating"] == "-1"){
                $sessionMemberFrage->setRating($sessionMemberFrage->getRating() - 1);
            }else{
                $sessionMemberFrage->setRating($sessionMemberFrage->getRating() - 1);
            }
            $sessionMemberFrage->setRatingCount($sessionMemberFrage->getRatingCount() + 1);
        }

        $session = $sessionMemberFrage->getSessionMember()[0]->getSession();
        $session->setCountRating($session->getCountRating() + 1);
        $entityManager->persist($session);
        $entityManager->flush();
        return new Response();
    }
}