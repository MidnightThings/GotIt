<?php

namespace App\DataFixtures;

use App\Entity\Frage;
use App\Entity\Kurs;
use App\Entity\Session;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class CombinedFixtures extends Fixture
{
    private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
        $this->passwordEncoder = $passwordEncoder;
     }

     private function createCourse(User $user): Kurs {

        $course = new Kurs();
        $course->setUser($user);
        $course->setName('Kurs0001');

        return $course;
    }

    private function createSession(Kurs $course): Session {
        $session = new Session();
        $session->setCode("ABCDEFG");
        $session->setStatus('IDLE');
        $session->setKurs($course);

        return $session;
    }

    private function createQuestions(Kurs $course): Array {
        $contents = 
        [
            'Ah! Where are you hiding? Happiness is looking for you. Come out in the sunshine. All the darkness will vanish and happiness will surround you.',
            'But the happiest people are the ones who understand that good things occur when one allows them to.',
            'Just because you are happy it does not mean that the day is perfect but that you have looked beyond its imperfections',
            'I do want more. I am not content with being happy. I was not made for it. It is not my destiny. My destiny is the opposite.',
            'What would it be like to look in the mirror and actually accept what you see? Not loathe the reflection, or despise it, or be resigned to it? But to like it?'
        ];

        $questions = [];
        $order = 0;

        foreach ($contents as $content) {
            $question = new Frage();
            $question->setKurs($course);
            $question->setContent($content);
            $question->setSortorder($order);
            $order++;

            $questions[] = $question;
        }

        return $questions;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername("test@user.de");
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'the_new_password'
        ));

        $newCourse = $this->createCourse($user);
        $newQuestions = $this->createQuestions($newCourse);
        $newSession = $this->createSession($newCourse);

        $manager->persist($user);
        $manager->persist($newCourse);
        foreach ($newQuestions as $newQuestion) {
            $manager->persist($newQuestion);
        }
        $manager->persist($newSession);
        $manager->flush();
    }

    
}
