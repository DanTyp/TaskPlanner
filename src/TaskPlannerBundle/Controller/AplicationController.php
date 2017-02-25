<?php

namespace TaskPlannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AplicationController extends Controller
{
    /**
     * @Route("/")
     */
    public function welcomeAction()
    {
        //return $this->render('TaskPlannerBundle:Aplication:welcome.html.twig');
        return $this->redirectToRoute('task_index');
    }

}
