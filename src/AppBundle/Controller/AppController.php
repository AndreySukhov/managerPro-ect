<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    /**
     * @Route("/", name="main")
     * @Method("GET")
     */
    public function mainAction()
    {
        return $this->redirectToRoute('app');
    }

    /**
     * @Route("/app", name="app")
     * @Method("GET")
     */
    public function appAction()
    {
        return $this->render('app/index.html.twig');
    }
}
