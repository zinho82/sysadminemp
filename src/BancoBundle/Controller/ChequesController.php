<?php

namespace BancoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Cheques;

/**
 * Cheques controller.
 *
 */
class ChequesController extends Controller
{
    /**
     * Lists all Cheques entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Cheques')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($cheques, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('BancoBundle:cheques:index.html.twig', array(
            'cheques' => $cheques,
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
        $filterForm = $this->createForm('BancoBundle\Form\ChequesFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('ChequesControllerFilter');
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
                $session->set('ChequesControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ChequesControllerFilter')) {
                $filterData = $session->get('ChequesControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('BancoBundle\Form\ChequesFilterType', $filterData);
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
            return $me->generateUrl('cheques', $requestParams);
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
     * Displays a form to create a new Cheques entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $cheque = new Cheques();
        $form   = $this->createForm('BancoBundle\Form\ChequesType', $cheque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cheque);
            $em->flush();
            
            $editLink = $this->generateUrl('cheques_edit', array('id' => $cheque->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New cheque was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'cheques' : 'cheques_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('BancoBundle:cheques:new_modal.html.twig', array(
            'cheque' => $cheque,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Cheques entity.
     *
     */
    public function showAction(Cheques $cheque)
    {
        $deleteForm = $this->createDeleteForm($cheque);
        return $this->render('BancoBundle:cheques:show.html.twig', array(
            'cheque' => $cheque,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Cheques entity.
     *
     */
    public function editAction(Request $request, Cheques $cheque)
    {
        $deleteForm = $this->createDeleteForm($cheque);
        $editForm = $this->createForm('BancoBundle\Form\ChequesType', $cheque);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cheque);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('cheques_edit', array('id' => $cheque->getId()));
        }
        return $this->render('BancoBundle:cheques:edit.html.twig', array(
            'cheque' => $cheque,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Cheques entity.
     *
     */
    public function deleteAction(Request $request, Cheques $cheque)
    {
    
        $form = $this->createDeleteForm($cheque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cheque);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Cheques was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Cheques');
        }
        
        return $this->redirectToRoute('cheques');
    }
    
    /**
     * Creates a form to delete a Cheques entity.
     *
     * @param Cheques $cheque The Cheques entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cheques $cheque)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cheques_delete', array('id' => $cheque->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Cheques by id
     *
     */
    public function deleteByIdAction(Cheques $cheque){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($cheque);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Cheques was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Cheques');
        }

        return $this->redirect($this->generateUrl('cheques'));

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
                $repository = $em->getRepository('BackendBundle:Cheques');

                foreach ($ids as $id) {
                    $cheque = $repository->find($id);
                    $em->remove($cheque);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'cheques was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the cheques ');
            }
        }

        return $this->redirect($this->generateUrl('cheques'));
    }
    

}
