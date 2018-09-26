<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BackendBundle\Entity\Notificaciones;
use BackendBundle\Entity\Facturas;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller {

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        return $this->render('HomeBundle:home:index.html.twig', array(
                    'notiFinanzas' => null,
                    'facturas' => null,
            'pantallas' =>  null,
        ));
    }

}
