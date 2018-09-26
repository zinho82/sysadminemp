<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BackendBundle\Entity\Notificaciones;
use BackendBundle\Entity\Facturas;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller {

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $Repo_notificaciones = $em->getRepository("BackendBundle:Notificaciones");
        $notiFinanzas = $Repo_notificaciones->CreateQueryBuilder('u')
                ->where('u.area = :area')
                ->orderBy('u.id', 'desc')
                ->setParameter('area', 175)
                ->getQuery()
        ;
        $paginador = $this->get('knp_paginator');
        $paginaFinanzas = $paginador->paginate($notiFinanzas, $request->query->getInt('page', 1), 5
        );
        $notiContabilidad = $Repo_notificaciones->CreateQueryBuilder('u')
                ->where('u.area = :area')
                ->orderBy('u.id', 'desc')
                ->setParameter('area', 176)
                ->getQuery();
        $repo_facturas = $em->getRepository("BackendBundle:Facturas");
        $panelFinanzas = $repo_facturas->createQueryBuilder('f')
                ->where('f.estado = :estado')
                ->orderBy('f.fechaemision', 'desc')
                ->setParameter('estado', 162)
                ->getQuery()
                ->getResult()
        ;
        $paginaPanelFinanzas = $paginador->paginate($panelFinanzas, $request->query->getInt('page', 1), 5
        );
        $Repo_pantallas = $em->getRepository("BackendBundle:PantallaClaro");
         $pantallas= $Repo_pantallas->CreateQueryBuilder('u')
                ->orderBy('u.horaIngreso', 'desc')
                ->getQuery();
         $paginaPanelPantallas = $paginador->paginate($pantallas, $request->query->getInt('page', 1), 5
        );
        return $this->render('HomeBundle:home:index.html.twig', array(
                    'notiFinanzas' => $paginaFinanzas,
                    'facturas' => $paginaPanelFinanzas,
            'pantallas' =>  $paginaPanelPantallas,
        ));
    }

}
