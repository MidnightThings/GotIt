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

        $answers = [
            "content" => "",
            "id" => ""
        ];

        if($status == "QUESTION"){
            $frage = $session->getFrage();
            $question = [
                "content" => $frage->getContent(),
                "id" => $frage->getId()
            ];
        }elseif($status == "RATE"){

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

        /**
         * 1. check if user exists
         * 2. create new answer
         */
        
        print_r($request);die;
        
        return new Response();
    }

    /**
     * @Route("/coursesession/rate", name="coursesession_rating", methods={"POST"})
     */
    public function courseSessionRating(EntityManagerInterface $entityManager, Request $request):Response
    {

        /**
         * 1. check if user exists
         * 2. check if answer exists
         * 3. change rating
         */
        
        print_r($request);die;
        
        return new Response();
    }
}