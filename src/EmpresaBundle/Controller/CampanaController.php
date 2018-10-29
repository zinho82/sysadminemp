<?php

namespace EmpresaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Campana;

/**
 * Campana controller.
 *
 */
class CampanaController extends Controller
{
    /**
     * Lists all Campana entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Campana')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($campanas, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('EmpresaBundle:campana:index.html.twig', array(
            'campanas' => $campanas,
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
        $filterForm = $this->createForm('EmpresaBundle\Form\CampanaFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('CampanaControllerFilter');
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
                $session->set('CampanaControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('CampanaControllerFilter')) {
                $filterData = $session->get('CampanaControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('EmpresaBundle\Form\CampanaFilterType', $filterData);
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
            return $me->generateUrl('campana', $requestParams);
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
     * Displays a form to create a new Campana entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $campana = new Campana();
        $form   = $this->createForm('EmpresaBundle\Form\CampanaType', $campana);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($campana);
            $em->flush();
            
            $editLink = $this->generateUrl('campana_edit', array('id' => $campana->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New campana was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'campana' : 'campana_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('EmpresaBundle:campana:new.html.twig', array(
            'campana' => $campana,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Campana entity.
     *
     */
    public function showAction(Campana $campana)
    {
        $deleteForm = $this->createDeleteForm($campana);
        return $this->render('EmpresaBundle:campana:show.html.twig', array(
            'campana' => $campana,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Campana entity.
     *
     */
    public function editAction(Request $request, Campana $campana)
    {
        $deleteForm = $this->createDeleteForm($campana);
        $editForm = $this->createForm('EmpresaBundle\Form\CampanaType', $campana);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($campana);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('campana_edit', array('id' => $campana->getId()));
        }
        return $this->render('EmpresaBundle:campana:edit.html.twig', array(
            'campana' => $campana,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Campana entity.
     *
     */
    public function deleteAction(Request $request, Campana $campana)
    {
    
        $form = $this->createDeleteForm($campana);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($campana);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Campana was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Campana');
        }
        
        return $this->redirectToRoute('campana');
    }
    
    /**
     * Creates a form to delete a Campana entity.
     *
     * @param Campana $campana The Campana entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Campana $campana)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('campana_delete', array('id' => $campana->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Campana by id
     *
     */
    public function deleteByIdAction(Campana $campana){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($campana);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Campana was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Campana');
        }

        return $this->redirect($this->generateUrl('campana'));

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
                $repository = $em->getRepository('BackendBundle:Campana');

                foreach ($ids as $id) {
                    $campana = $repository->find($id);
                    $em->remove($campana);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'campanas was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the campanas ');
            }
        }

        return $this->redirect($this->generateUrl('campana'));
    }
    

}
