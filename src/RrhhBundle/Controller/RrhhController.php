<?php

namespace RrhhBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Rrhh;

/**
 * Rrhh controller.
 *
 */
class RrhhController extends Controller
{
    /**
     * Lists all Rrhh entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Rrhh')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($rrhhs, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('RrhhBundle:rrhh:index.html.twig', array(
            'rrhhs' => $rrhhs,
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
        $filterForm = $this->createForm('RrhhBundle\Form\RrhhFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('RrhhControllerFilter');
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
                $session->set('RrhhControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('RrhhControllerFilter')) {
                $filterData = $session->get('RrhhControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('RrhhBundle\Form\RrhhFilterType', $filterData);
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
            return $me->generateUrl('rrhh', $requestParams);
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
     * Displays a form to create a new Rrhh entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $rrhh = new Rrhh();
        $form   = $this->createForm('RrhhBundle\Form\RrhhType', $rrhh);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rrhh);
            $em->flush();
            
            $editLink = $this->generateUrl('rrhh_edit', array('id' => $rrhh->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New rrhh was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'rrhh' : 'rrhh_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('RrhhBundle:rrhh:new.html.twig', array(
            'rrhh' => $rrhh,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Rrhh entity.
     *
     */
    public function showAction(Rrhh $rrhh)
    {
        $deleteForm = $this->createDeleteForm($rrhh);
        return $this->render('RrhhBundle:rrhh:show.html.twig', array(
            'rrhh' => $rrhh,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Rrhh entity.
     *
     */
    public function editAction(Request $request, Rrhh $rrhh)
    {
        $deleteForm = $this->createDeleteForm($rrhh);
        $editForm = $this->createForm('RrhhBundle\Form\RrhhType', $rrhh);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rrhh);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('rrhh_edit', array('id' => $rrhh->getId()));
        }
        return $this->render('RrhhBundle:rrhh:edit.html.twig', array(
            'rrhh' => $rrhh,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Rrhh entity.
     *
     */
    public function deleteAction(Request $request, Rrhh $rrhh)
    {
    
        $form = $this->createDeleteForm($rrhh);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rrhh);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Rrhh was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Rrhh');
        }
        
        return $this->redirectToRoute('rrhh');
    }
    
    /**
     * Creates a form to delete a Rrhh entity.
     *
     * @param Rrhh $rrhh The Rrhh entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Rrhh $rrhh)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rrhh_delete', array('id' => $rrhh->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Rrhh by id
     *
     */
    public function deleteByIdAction(Rrhh $rrhh){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($rrhh);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Rrhh was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Rrhh');
        }

        return $this->redirect($this->generateUrl('rrhh'));

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
                $repository = $em->getRepository('BackendBundle:Rrhh');

                foreach ($ids as $id) {
                    $rrhh = $repository->find($id);
                    $em->remove($rrhh);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'rrhhs was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the rrhhs ');
            }
        }

        return $this->redirect($this->generateUrl('rrhh'));
    }
    

}
