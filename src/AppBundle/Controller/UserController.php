<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Usuario;
use AppBundle\Form\RegisterType;
use AppBundle\Form\UserType;

class UserController extends Controller {

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function portadaAction(Request $request) {

        return $this->render('AppBundle:Portada:portada.html.twig');
    }

    public function loginAction(Request $request) {
        if (is_object($this->getUser())) {
            return $this->redirect('home');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AppBundle:User:login.html.twig', array(
                    "lastusername" => $lastUsername,
                    'error' => $error
        ));
    }

    public function registerAction(Request $request) {
        if (is_object($this->getUser())) {
            return $this->redirect('home');
        }
        $user = new Usuario();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                //$user_repo=$em->getRepository("BackendBundle;User");

                $query = $em->createQuery("select u from BackendBundle:Usuario u where u.email=:email ")
                        ->setParameter('email', $form->get('email')->getData());
                $user_isset = $query->GetResult();

                if (count($user_isset) == 0) {
                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($user);
                    $password = $encoder->encodePassword($form->get("password")->getData(), $user->getSalt());

                    $user->setPassword($password);
                    $user->setRole("ROLE_USER");
                    $user->setApm('');
                    $user->setDireccion('');
                    $user->setComuna('');
                    $user->setRegion('');
                    $user->setImage('null');
                    $user->setCiudad('');

                    $em->persist($user);

                    $flush = $em->flush();
                    if ($flush == null) {
                        $status = "Te has Registrado";
                        $this->session->getFlashBag()->add("status", $status);
                        return $this->redirect("login");
                    } else {
                        $status = "No te haz Registrado";
                    }
                } else {
                    $status = "El usuario ya existe !!";
                }
            } else {
                $status = "No te has Registrado!!";
            }
            $this->session->getFlashBag()->add("status", $status);
        }




        return $this->render("AppBundle:User:register.html.twig", array(
                    "form" => $form->createView()
        ));
    }

    public function homeAction(Request $request) {
        $em=  $this->getDoctrine()->getManager();
        $lotes = "select count(*) as cantidad,l.container,l.number_Pallets,l.comoditty  from lotes l group by l.container,l.number_Pallets
 
"; 
        $stmt = $em->getConnection()->prepare($lotes);
        $stmt->execute();
        return $this->render("AppBundle:User:home.html.twig", array(
            'lotes' =>  $stmt,
        ));
    }

    public function nickTestAction(Request $request) {
        $nick = $request->get('nick');
        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository("BackendBundle:Usuario");
        $user_isser = $user_repo->findOneBy(array("username" => $nick));
        $result = "used";
        if (count($user_isser >= 1 && is_object($user_isser))) {
            $result = "used";
        } else {
            $result = "unused";
        }
        return new Response($result);
    }

    public function editUserAction(Request $request) {
        $user = $this->getUser();
        $user_image = $user->getImage();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $query = $em->createQuery("select u from BackendBundle:Usuario u where u.email=:email ")
                        ->setParameter('email', $form->get('email')->getData());
                $user_isset = $query->GetResult();

                if (($user->getEmail() == $user_isset[0]->getEmail() ) || count($user_isset) == 0) {
                    //UPLOAD FILE
                    $file = $form['image']->getData(); 
                    if (!empty($file) && $file != null) {
                        $ext = $file->guessExtension();
                        if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg') {
                            $file_name = $user->getIdusuario() . time() . "." . $ext;
                            $file->move("uploads/users", $file_name);
                            $user->setImage($file_name);
                        } else {
                            $user->setImage($user_image);
                        }
                    } else {
                        $user->setImage($user_image);
                    }

                    $em->persist($user);

                    $flush = $em->flush();
                    if ($flush == null) {
                        $status .= "Haz Modificado Correctamente";
                    } else {
                        $status .= "No Haz Modificado Datos";
                    }
                } else {
                    $status .= "El usuario ya existe !!";
                }
            } else {
                $status .= "No se han Actulizado Los Datos";
            }
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirect("my-data");
        }




        return $this->render("AppBundle:User:edit_user.html.twig", array(
                    'form' => $form->createView()
        ));
    }

    public function usersAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = "select u from BackendBundle:User u order by u.id asc";
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 5);


        return $this->render("AppBundle:User:users.html.twig", array(
                    'pagination' => $pagination
        ));
    }

    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $search = trim($request->query->get("search", null));
        if ($search == null) {
            return $this->redirect($this->generateUrl("home_publications"));
        }
        $dql = "select u from BackendBundle:User u"
                . " where u.name like :search or u.surname like :search or  u.nick like :search "
                . "order by u.id asc";
        $query = $em->createQuery($dql)->setParameter('search', "%$search%");
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 5);


        return $this->render("AppBundle:User:users.html.twig", array(
                    'pagination' => $pagination
        ));
    }

    public function profileAction(Request $request, $nickname = null) {
        $em = $this->getDoctrine()->getManager();
        if ($nickname != null) {
            $user_repo = $em->getRepository("BackendBundle:User");
            $user = $user_repo->findOneBy(array('nick' => $nickname));
        } else {
            $user = $this->getUser();
        }
        if (empty($user) || !is_object($user)) {
            return $this->redirect($this->generateUrl('home_publications'));
        }
        $user_id = $user->getId();
        $dql = "select p from BackendBundle:Publication p where p.user=$user_id order by p.id desc";
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $publications = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 5);
        return $this->render('AppBundle:User:profile.html.twig', array(
                    'user' => $user,
                    'pagination' => $publications
        ));
    }

    

}
