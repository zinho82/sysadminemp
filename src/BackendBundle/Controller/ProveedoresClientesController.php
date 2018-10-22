<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\ProveedoresClientes;

/**
 * ProveedoresClientes controller.
 *
 */
class ProveedoresClientesController extends Controller
{
    /**
     * Lists all ProveedoresClientes entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:ProveedoresClientes')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($proveedoresClientes, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('proveedoresclientes/index.html.twig', array(
            'proveedoresClientes' => $proveedoresClientes,
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
        $filterForm = $this->createForm('BackendBundle\Form\ProveedoresClientesFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('ProveedoresClientesControllerFilter');
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
                $session->set('ProveedoresClientesControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ProveedoresClientesControllerFilter')) {
                $filterData = $session->get('ProveedoresClientesControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('BackendBundle\Form\ProveedoresClientesFilterType', $filterData);
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
            return $me->generateUrl('proveedoresclientes', $requestParams);
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
     * Displays a form to create a new ProveedoresClientes entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $proveedoresCliente = new ProveedoresClientes();
        $form   = $this->createForm('BackendBundle\Form\ProveedoresClientesType', $proveedoresCliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedoresCliente);
            $em->flush();
            
            $editLink = $this->generateUrl('proveedoresclientes_edit', array('id' => $proveedoresCliente->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New proveedoresCliente was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'proveedoresclientes' : 'proveedoresclientes_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('proveedoresclientes/new.html.twig', array(
            'proveedoresCliente' => $proveedoresCliente,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a ProveedoresClientes entity.
     *
     */
    public function showAction(ProveedoresClientes $proveedoresCliente)
    {
        $deleteForm = $this->createDeleteForm($proveedoresCliente);
        return $this->render('proveedoresclientes/show.html.twig', array(
            'proveedoresCliente' => $proveedoresCliente,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing ProveedoresClientes entity.
     *
     */
    public function editAction(Request $request, ProveedoresClientes $proveedoresCliente)
    {
        $deleteForm = $this->createDeleteForm($proveedoresCliente);
        $editForm = $this->createForm('BackendBundle\Form\ProveedoresClientesType', $proveedoresCliente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedoresCliente);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('proveedoresclientes_edit', array('id' => $proveedoresCliente->getId()));
        }
        return $this->render('proveedoresclientes/edit.html.twig', array(
            'proveedoresCliente' => $proveedoresCliente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a ProveedoresClientes entity.
     *
     */
    public function deleteAction(Request $request, ProveedoresClientes $proveedoresCliente)
    {
    
        $form = $this->createDeleteForm($proveedoresCliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($proveedoresCliente);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The ProveedoresClientes was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the ProveedoresClientes');
        }
        
        return $this->redirectToRoute('proveedoresclientes');
    }
    
    /**
     * Creates a form to delete a ProveedoresClientes entity.
     *
     * @param ProveedoresClientes $proveedoresCliente The ProveedoresClientes entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProveedoresClientes $proveedoresCliente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('proveedoresclientes_delete', array('id' => $proveedoresCliente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete ProveedoresClientes by id
     *
     */
    public function deleteByIdAction(ProveedoresClientes $proveedoresCliente){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($proveedoresCliente);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The ProveedoresClientes was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the ProveedoresClientes');
        }

        return $this->redirect($this->generateUrl('proveedoresclientes'));

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
                $repository = $em->getRepository('BackendBundle:ProveedoresClientes');

                foreach ($ids as $id) {
                    $proveedoresCliente = $repository->find($id);
                    $em->remove($proveedoresCliente);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'proveedoresClientes was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the proveedoresClientes ');
            }
        }

        return $this->redirect($this->generateUrl('proveedoresclientes'));
    }
    

}
