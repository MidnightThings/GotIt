<?php

namespace App\DataFixtures;

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

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername("test@user.de");
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'the_new_password'
        ));

        $newCourse = $this->createCourse($user);
        $newSession = $this->createSession($newCourse);

        $manager->persist($user);
        $manager->persist($newCourse);
        $manager->persist($newSession);
        $manager->flush();
    }

    
}
