<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class LikedExtension extends \Twig_Extension {

    protected $doctrine;

    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('liked', array($this, 'likedFilter'))
        );
    }

    public function likedFilter($user, $publication) {
        $liked_repo= $this->doctrine->getRepository('BackendBundle:Like');
        
        $publication_Liked=$liked_repo->findOneBy(array(
            'user' => $user,
            'publication' => $publication
                ));
        if(!empty($publication_Liked) && (is_object($publication_Liked))){
            $result=true;
        }else{
            $result=false;
        }
        return $result;
    }

    public function getName() {
        return 'liked_extension';
    }

}
