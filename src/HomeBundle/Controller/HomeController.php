<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BackendBundle\Entity\Notificaciones;
use BackendBundle\Entity\Facturas;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller {

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo_factura = $em->getRepository("BackendBundle:Facturas");
        $Facturas = $repo_factura->createQueryBuilder('c')
                        ->where("c.estadoPago != 27")
                        ->orderBy('c.fechaIngreso', 'desc')
                        ->getQuery()->getResult();
        $Notificaciones=$em->getRepository("BackendBundle:Notificaciones");
        $Notificaciones=$Notificaciones->createQueryBuilder('c')
    ->orderBy("c.fecha","desc")
    ->getQuery()->getResult();
        $paginator  = $this->get('knp_paginator');
    $Notificaciones = $paginator->paginate(
        $Notificaciones, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        10/*limit per page*/
            );
    $Facturas= $paginator->paginate(
        $Facturas, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        10/*limit per page*/
            );
        return $this->render('HomeBundle:home:index.html.twig', array(
                    'notiFinanzas' => null,
                    'notifica' => $Notificaciones,
                    'facturas' => $Facturas,
                    'pantallas' => null,
        ));
    }

}
