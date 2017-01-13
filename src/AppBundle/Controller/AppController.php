<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    /**
     * Редирект на приложение.
     *
     * @Route("/", name="main")
     * @Method("GET")
     */
    public function mainAction()
    {
        return $this->redirectToRoute('app');
    }

    /**
     * Приложение.
     *
     * @Route("/app", name="app")
     * @Method("GET")
     */
    public function appAction()
    {
        return $this->render('app/index.html.twig');
    }
}
