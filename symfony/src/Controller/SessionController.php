<?php

namespace App\Controller;

use App\Entity\Kurs;
use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends AbstractController
{
    /**
     * @Route("/session/start/{courseid}", name="session_start")
     */
    public function startSession($courseid, EntityManagerInterface $entityManager): Response
    {
        $course = $entityManager->getRepository(Kurs::class)->find($courseid);
        $code = $this->generateRandomCode(6);
        $session = new Session();
        $session->setCode($code);
        $session->setStatus('IDLE');
        $session->setKurs($course);

        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirect("/session/" . $code);
    }

    /**
     * @Route("/session/{id}", name="session")
     */
    public function goToSession($id, EntityManagerInterface $entityManager): Response
    {
        return $this->render("session/session.html.twig", ['code' => $id]);
    }

    function generateRandomCode($n) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
      
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
      
        return $randomString;
    }
}