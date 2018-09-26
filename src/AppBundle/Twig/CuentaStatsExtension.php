<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class CuentaStatsExtension extends \Twig_Extension {

    protected $doctrine;

    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('cuenta_stats', array($this, 'cuentaStatsFilter'))
        );
    }

    public function cuentaStatsFilter($user) {
        $inversion_repo = $this->doctrine->getRepository('BackendBundle:Ctacte');

        $user_Inversion=$inversion_repo->createQueryBuilder("ct")
                ->andWhere('ct.movimiento=:tipo')
                ->andWhere('ct.usuariousuario=:usuario')
                ->setParameter('tipo','Inversion')
                ->setParameter('usuario',$user)
                ->select('sum(ct.monto) as total ')
                ->getQuery()
                ->getSingleScalarResult();
        $user_utilidad=$inversion_repo->createQueryBuilder("ct")
                ->andWhere('ct.movimiento=:tipo')
                ->andWhere('ct.usuariousuario=:usuario')
                ->setParameter('tipo','Utilidades')
                ->setParameter('usuario',$user)
                ->select('sum(ct.monto) as total ')
                ->getQuery()
                ->getSingleScalarResult();
        $result = array(
            'inversion' => $user_Inversion, 
            'utilidad' => $user_utilidad
        );
        return $result;
    }

    public function getName() {
        return 'user_stats_extension';
    }

}
