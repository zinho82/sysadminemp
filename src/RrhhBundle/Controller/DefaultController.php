<?php

namespace RrhhBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RrhhBundle:Default:index.html.twig');
    }
}
