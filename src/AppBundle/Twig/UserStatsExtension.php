<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class UserStatsExtension extends \Twig_Extension {

    protected $doctrine;

    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('user_stats', array($this, 'userStatsFilter'))
        );
    }

    public function userStatsFilter($user) {
         $em = $this->getDoctrine()->getManager();
     $dql="select sum(deposito) as depo,sum(utilidad) as util,sum(salida) as inver, sum(reservado) as reser from BackendBundle:Ctacte c  where idusuario=$user";
     $estados=$em->createQuery($dql);
     
        return $estados;
    }

    public function getName() {
        return 'user_stats_extension';
    }

}
