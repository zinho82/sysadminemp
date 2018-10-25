<?php

namespace NotificacionesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Notificaciones;

/**
 * Notificaciones controller.
 *
 */
class NotificacionesController extends Controller
{
    /**
     * Lists all Notificaciones entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Notificaciones')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($notificaciones, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('NotificacionesBundle:notificaciones:index.html.twig', array(
            'notificaciones' => $notificaciones,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,

        ));
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, Request $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm('NotificacionesBundle\Form\NotificacionesFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('NotificacionesControllerFilter');
        }

        // Filter action
        if ($request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->handleRequest($request);

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('NotificacionesControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('NotificacionesControllerFilter')) {
                $filterData = $session->get('NotificacionesControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('NotificacionesBundle\Form\NotificacionesFilterType', $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }


    /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder, Request $request)
    {
        //sorting
        $sortCol = $queryBuilder->getRootAlias().'.'.$request->get('pcg_sort_col', 'id');
        $queryBuilder->orderBy($sortCol, $request->get('pcg_sort_order', 'desc'));
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($request->get('pcg_show' , 10));

        try {
            $pagerfanta->setCurrentPage($request->get('pcg_page', 1));
        } catch (\Pagerfanta\Exception\OutOfRangeCurrentPageException $ex) {
            $pagerfanta->setCurrentPage(1);
        }
        
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me, $request)
        {
            $requestParams = $request->query->all();
            $requestParams['pcg_page'] = $page;
            return $me->generateUrl('notificaciones', $requestParams);
        };

        // Paginator - view
        $view = new TwitterBootstrap3View();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => 'previous',
            'next_message' => 'next',
        ));

        return array($entities, $pagerHtml);
    }
    
    
    
    /*
     * Calculates the total of records string
     */
    protected function getTotalOfRecordsString($queryBuilder, $request) {
        $totalOfRecords = $queryBuilder->select('COUNT(e.id)')->getQuery()->getSingleScalarResult();
        $show = $request->get('pcg_show', 10);
        $page = $request->get('pcg_page', 1);

        $startRecord = ($show * ($page - 1)) + 1;
        $endRecord = $show * $page;

        if ($endRecord > $totalOfRecords) {
            $endRecord = $totalOfRecords;
        }
        return "Showing $startRecord - $endRecord of $totalOfRecords Records.";
    }
    
    

    /**
     * Displays a form to create a new Notificaciones entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $notificacione = new Notificaciones();
        $form   = $this->createForm('NotificacionesBundle\Form\NotificacionesType', $notificacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $notificacione->setFecha(new \DateTime);
            $notificacione->setUsuario($this->getUser());
            $em->persist($notificacione);
            $em->flush();
            
            $editLink = $this->generateUrl('notificaciones_edit', array('id' => $notificacione->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New notificacione was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'notificaciones' : 'notificaciones_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('NotificacionesBundle:notificaciones:new.html.twig', array(
            'notificacione' => $notificacione,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Notificaciones entity.
     *
     */
    public function showAction(Notificaciones $notificacione)
    {
        $deleteForm = $this->createDeleteForm($notificacione);
        return $this->render('NotificacionesBundle:notificaciones:show.html.twig', array(
            'notificacione' => $notificacione,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Notificaciones entity.
     *
     */
    public function editAction(Request $request, Notificaciones $notificacione)
    {
        $deleteForm = $this->createDeleteForm($notificacione);
        $editForm = $this->createForm('NotificacionesBundle\Form\NotificacionesType', $notificacione);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($notificacione);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('notificaciones_edit', array('id' => $notificacione->getId()));
        }
        return $this->render('NotificacionesBundle:notificaciones:edit.html.twig', array(
            'notificacione' => $notificacione,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Notificaciones entity.
     *
     */
    public function deleteAction(Request $request, Notificaciones $notificacione)
    {
    
        $form = $this->createDeleteForm($notificacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($notificacione);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Notificaciones was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Notificaciones');
        }
        
        return $this->redirectToRoute('notificaciones');
    }
    
    /**
     * Creates a form to delete a Notificaciones entity.
     *
     * @param Notificaciones $notificacione The Notificaciones entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Notificaciones $notificacione)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('notificaciones_delete', array('id' => $notificacione->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Notificaciones by id
     *
     */
    public function deleteByIdAction(Notificaciones $notificacione){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($notificacione);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Notificaciones was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Notificaciones');
        }

        return $this->redirect($this->generateUrl('notificaciones'));

    }
    

    /**
    * Bulk Action
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('BackendBundle:Notificaciones');

                foreach ($ids as $id) {
                    $notificacione = $repository->find($id);
                    $em->remove($notificacione);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'notificaciones was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the notificaciones ');
            }
        }

        return $this->redirect($this->generateUrl('notificaciones'));
    }
    

}
