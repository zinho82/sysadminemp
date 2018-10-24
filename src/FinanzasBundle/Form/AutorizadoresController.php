<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Autorizadores;

/**
 * Autorizadores controller.
 *
 */
class AutorizadoresController extends Controller
{
    /**
     * Lists all Autorizadores entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Autorizadores')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($autorizadores, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('autorizadores/index.html.twig', array(
            'autorizadores' => $autorizadores,
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
        $filterForm = $this->createForm('BackendBundle\Form\AutorizadoresFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('AutorizadoresControllerFilter');
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
                $session->set('AutorizadoresControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('AutorizadoresControllerFilter')) {
                $filterData = $session->get('AutorizadoresControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('BackendBundle\Form\AutorizadoresFilterType', $filterData);
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
            return $me->generateUrl('autorizadores', $requestParams);
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
     * Displays a form to create a new Autorizadores entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $autorizadore = new Autorizadores();
        $form   = $this->createForm('BackendBundle\Form\AutorizadoresType', $autorizadore);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($autorizadore);
            $em->flush();
            
            $editLink = $this->generateUrl('autorizadores_edit', array('id' => $autorizadore->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New autorizadore was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'autorizadores' : 'autorizadores_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('autorizadores/new.html.twig', array(
            'autorizadore' => $autorizadore,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Autorizadores entity.
     *
     */
    public function showAction(Autorizadores $autorizadore)
    {
        $deleteForm = $this->createDeleteForm($autorizadore);
        return $this->render('autorizadores/show.html.twig', array(
            'autorizadore' => $autorizadore,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Autorizadores entity.
     *
     */
    public function editAction(Request $request, Autorizadores $autorizadore)
    {
        $deleteForm = $this->createDeleteForm($autorizadore);
        $editForm = $this->createForm('BackendBundle\Form\AutorizadoresType', $autorizadore);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($autorizadore);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('autorizadores_edit', array('id' => $autorizadore->getId()));
        }
        return $this->render('autorizadores/edit.html.twig', array(
            'autorizadore' => $autorizadore,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Autorizadores entity.
     *
     */
    public function deleteAction(Request $request, Autorizadores $autorizadore)
    {
    
        $form = $this->createDeleteForm($autorizadore);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($autorizadore);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Autorizadores was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Autorizadores');
        }
        
        return $this->redirectToRoute('autorizadores');
    }
    
    /**
     * Creates a form to delete a Autorizadores entity.
     *
     * @param Autorizadores $autorizadore The Autorizadores entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Autorizadores $autorizadore)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('autorizadores_delete', array('id' => $autorizadore->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Autorizadores by id
     *
     */
    public function deleteByIdAction(Autorizadores $autorizadore){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($autorizadore);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Autorizadores was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Autorizadores');
        }

        return $this->redirect($this->generateUrl('autorizadores'));

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
                $repository = $em->getRepository('BackendBundle:Autorizadores');

                foreach ($ids as $id) {
                    $autorizadore = $repository->find($id);
                    $em->remove($autorizadore);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'autorizadores was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the autorizadores ');
            }
        }

        return $this->redirect($this->generateUrl('autorizadores'));
    }
    

}
