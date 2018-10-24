<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BackendBundle\Entity\Notificaciones;
use BackendBundle\Entity\Facturas;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller {

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $Notificaciones=$em->getRepository("BackendBundle:Notificaciones")->findAll();
        return $this->render('HomeBundle:home:index.html.twig', array(
                    'notiFinanzas' => null,
                    'facturas' => null,
            'notifica'  => $Notificaciones,
            'pantallas' =>  null,
        ));
    }

}
