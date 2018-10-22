<?php

namespace FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FinanzasBundle:Default:index.html.twig');
    }
}
