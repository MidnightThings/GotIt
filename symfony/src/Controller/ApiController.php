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
                $answerResult = $sessionRepository->getAnswers($token, $session->getId());
                shuffle($answerResult);
                $counter = 0;
                foreach($answerResult as $answer){
                    if($counter == 3){
                        break;
                    }
                    if($answer["id"] == ""){
                        continue;
                    }
                    
                    $answerObject = $entityManager->getRepository(SessionMemberFrage::class)->find($answer["id"]);
                    $sessionMember->addTmpRateAnswer($answerObject);
                    $entityManager->persist($sessionMember);
                    $entityManager->flush();
                    $answerArray[] = $answer;
                    $counter = $counter + 1;
                }

            }else{
                foreach($answerObject as $answer){
                    $answerArray[] = ["content" => $answer->getContent(), "id" => $answer->getId()];
                }
            }
            $answers = $answerArray ?? [];
        }elseif($status == "IDLE"){
            $sessionMembers = $session->getSessionMembers();
            $session->setCountRatings(0);
            $entityManager->persist($session);
            $entityManager->flush();

            foreach($sessionMembers as $member){
                $entityManager->getConnection()->executeQuery('delete FROM session_member_session_member_frage WHERE session_member_id = ' . $member->getId());
            }
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

        $requestContent = json_decode($request->getContent())->data;
        $frage = $entityManager->getRepository(Frage::class)->find($requestContent->questionId);
        if(!isset($frage)) {
            return new Response();
        }
        
        $sessionMember =  $entityManager->getRepository(SessionMember::class)->find($requestContent->token);
        if(!isset($sessionMember)) {
            return new Response();
        }
        $sessionMemberFrage = new SessionMemberFrage();
        $sessionMemberFrage->setSessionmember($sessionMember);
        $sessionMemberFrage->setFrage($frage);
        $sessionMemberFrage->setContent($requestContent->answer);
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
        $requestContent = json_decode($request->getContent())->data;
        
        foreach($requestContent->ratings as $rating){
            $sessionMemberFrage = $entityManager->getRepository(SessionMemberFrage::class)->find($rating->answerId);
            if(!isset($sessionMemberFrage)){
                return new Response();
            }
            if($rating->rating == "-1"){
                $sessionMemberFrage->setRating($sessionMemberFrage->getRating() - 1);
            }else{
                $sessionMemberFrage->setRating($sessionMemberFrage->getRating() + 1);
            }
            $sessionMemberFrage->setRatingCount($sessionMemberFrage->getRatingCount() + 1);
            $entityManager->persist($sessionMemberFrage);
            $entityManager->flush();
        }
        
        $session = $sessionMemberFrage->getSessionMember()->getSession();
        $session->setCountRatings($session->getCountRatings() + 1);
        $entityManager->persist($session);
        $entityManager->flush();
        return new Response();
    }
}