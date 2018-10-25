<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BackendBundle\Entity\Notificaciones;
use BackendBundle\Entity\Facturas;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller {

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $Notificaciones = $em->getRepository("BackendBundle:Notificaciones")->findAll();
        $repo_factura = $em->getRepository("BackendBundle:Facturas");
        $Facturas = $repo_factura->createQueryBuilder('c')
                        ->where("c.estadoPago != 27")
                        ->orderBy('c.fechaIngreso', 'desc')
                        ->getQuery()->getResult();
        return $this->render('HomeBundle:home:index.html.twig', array(
                    'notiFinanzas' => null,
                    'facturas' => null,
                    'notifica' => $Notificaciones,
                    'facturas' => $Facturas,
                    'pantallas' => null,
        ));
    }

}
