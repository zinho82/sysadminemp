<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Inventario;

/**
 * Inventario controller.
 *
 */
class InventarioController extends Controller
{
    /**
     * Lists all Inventario entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Inventario')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($inventarios, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('inventario/index.html.twig', array(
            'inventarios' => $inventarios,
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
        $filterForm = $this->createForm('BackendBundle\Form\InventarioFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('InventarioControllerFilter');
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
                $session->set('InventarioControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('InventarioControllerFilter')) {
                $filterData = $session->get('InventarioControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('BackendBundle\Form\InventarioFilterType', $filterData);
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
            return $me->generateUrl('inventario', $requestParams);
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
     * Displays a form to create a new Inventario entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $inventario = new Inventario();
        $form   = $this->createForm('BackendBundle\Form\InventarioType', $inventario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inventario);
            $em->flush();
            
            $editLink = $this->generateUrl('inventario_edit', array('id' => $inventario->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New inventario was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'inventario' : 'inventario_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('inventario/new.html.twig', array(
            'inventario' => $inventario,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Inventario entity.
     *
     */
    public function showAction(Inventario $inventario)
    {
        $deleteForm = $this->createDeleteForm($inventario);
        return $this->render('inventario/show.html.twig', array(
            'inventario' => $inventario,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Inventario entity.
     *
     */
    public function editAction(Request $request, Inventario $inventario)
    {
        $deleteForm = $this->createDeleteForm($inventario);
        $editForm = $this->createForm('BackendBundle\Form\InventarioType', $inventario);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inventario);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('inventario_edit', array('id' => $inventario->getId()));
        }
        return $this->render('inventario/edit.html.twig', array(
            'inventario' => $inventario,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Inventario entity.
     *
     */
    public function deleteAction(Request $request, Inventario $inventario)
    {
    
        $form = $this->createDeleteForm($inventario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($inventario);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Inventario was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Inventario');
        }
        
        return $this->redirectToRoute('inventario');
    }
    
    /**
     * Creates a form to delete a Inventario entity.
     *
     * @param Inventario $inventario The Inventario entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Inventario $inventario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('inventario_delete', array('id' => $inventario->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Inventario by id
     *
     */
    public function deleteByIdAction(Inventario $inventario){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($inventario);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Inventario was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Inventario');
        }

        return $this->redirect($this->generateUrl('inventario'));

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
                $repository = $em->getRepository('BackendBundle:Inventario');

                foreach ($ids as $id) {
                    $inventario = $repository->find($id);
                    $em->remove($inventario);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'inventarios was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the inventarios ');
            }
        }

        return $this->redirect($this->generateUrl('inventario'));
    }
    

}
