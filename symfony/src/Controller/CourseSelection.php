<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityManagerInterface;


use App\test\Course; //delete later

class CourseSelection extends AbstractController
{
    /**
     * @Route("/courseselection", name="courseselection")
     */
    public function courseSelection(): Response
    {
       // $courses = [];

        //$courses = getCourses();

        //delete later
        $course1 = new Course();
        $course1->id = 1;
        $course1->name = 'course1';

        $course2 = new Course();
        $course2->id = 2;
        $course2->name = 'course2';

        $course3 = new Course();
        $course3->id = 3;
        $course3->name = 'course3';

        $course4 = new Course();
        $course4->id = 4;
        $course4->name = 'course4';

        $courses = array($course1, $course2, $course3, $course4);
        //delete later


        return $this->render("courseselection/courseselection.html.twig", ['courses' => $courses]);
    }



    public function selectCourse($id)
    {
        //call api to get course
    }

    public function getCourses()
    {
        //call api to get all courses

        $courses = [];

        return $courses;
    }

    public function addCourse($course)
    {
        //call api to add course
    }

    public function editCourse($id)
    {
        //call api to edit course
    }

    public function deleteCourse($id)
    {
        //call api to delete course
    }

}
