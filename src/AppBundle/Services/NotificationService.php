<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Services;
use BackendBundle\Entity\Notificaciones;
/**
 * Description of NotificationServices
 *
 * @author zinho
 */ 
class NotificationService {
    public  $manager;
 
    public function __construct($manager){ 
        $this->manager=$manager;
                
    } 
    
    public function set($texto,$user,$area) {
        $em= $this->manager;
        $areas=$em->getRepository("BackendBundle:Departamentos")->find($area);
        $notification=new Notificaciones();
        $notification->setUsuario($user);
        $notification->setFecha(new \DateTime);
        $notification->setDescripcion($texto);
        $notification->setArea($areas);
        $em->persist($notification);
        $flush=$em->flush();
        if($flush==null){
            $status=true;
        }else{
            $status=false;
        }
        return $status;
        
    }
    public function read($user) {
        $em= $this->manager;
        $notification_repo=$em->getRepository('BackendBundle:Movimientos');
        $notificarions=$notification_repo->findBy(array('usuariousuario' => $user));
        foreach ($notificarions as $notificarion){
            $notificarion->setReaded(1);
            $em->persist($notificarion);
        }
        $flush=$em->flush();
        if($flush===null){
            return true;
        }else{
            return FALSE;
            
        }
    }
}
