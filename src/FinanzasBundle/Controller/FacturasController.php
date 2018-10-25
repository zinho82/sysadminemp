<?php

namespace FinanzasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Facturas;

/**
 * Facturas controller.
 *
 */
class FacturasController extends Controller
{
    /**
     * Lists all Facturas entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Facturas')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($facturas, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('FinanzasBundle:facturas:index.html.twig', array(
            'facturas' => $facturas,
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
        $filterForm = $this->createForm('FinanzasBundle\Form\FacturasFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('FacturasControllerFilter');
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
                $session->set('FacturasControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('FacturasControllerFilter')) {
                $filterData = $session->get('FacturasControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('FinanzasBundle\Form\FacturasFilterType', $filterData);
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
            return $me->generateUrl('facturas', $requestParams);
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
     * Displays a form to create a new Facturas entity.
     *
     */
    public function newAction(Request $request,$id=null)
    {
    
        $factura = new Facturas();
        $form   = $this->createForm('FinanzasBundle\Form\FacturasType', $factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $oc= $em->getRepository("BackendBundle:Ordenescompra")->find($id);
            $pro=$em->getRepository("BackendBundle:Proveedoresclientes")->find($oc->getProveedoresclientes());
            $empre=$em->getRepository("BackendBundle:Empresa")->find($oc->getEmpresa());
            $factura->setEmpresa($empre);
            $factura->setOrdenescompra($oc);
            $factura->setProveedoresClientes($pro);
            $factura->setFechaIngreso(new \DateTime);
            $em->persist($factura);
            if ($em->flush() == null) {
                $notificacion = $this->get('app.notification_service');
                $notificacion->set("Se ha ingresado una nueva Factura: N° " . $factura->getNumerofactura() . ", y se encuentra  " . $factura->getEstadoPago() . '. Correspondiente a la OC: '.$factura->getOrdenescompra(), $this->getUser(), $this->getUser()->getRrhh()->getDepartamento());
            }
            $editLink = $this->generateUrl('facturas_edit', array('id' => $factura->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New factura was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'facturas' : 'facturas_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('FinanzasBundle:facturas:new.html.twig', array(
            'factura' => $factura,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Facturas entity.
     *
     */
    public function showAction(Facturas $factura)
    {
        $deleteForm = $this->createDeleteForm($factura);
        return $this->render('FinanzasBundle:facturas:show.html.twig', array(
            'factura' => $factura,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Facturas entity.
     *
     */
    public function editAction(Request $request, Facturas $factura)
    {
        $deleteForm = $this->createDeleteForm($factura);
        $editForm = $this->createForm('FinanzasBundle\Form\FacturasType', $factura);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($factura);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('facturas_edit', array('id' => $factura->getId()));
        }
        return $this->render('FinanzasBundle:facturas:edit.html.twig', array(
            'factura' => $factura,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Facturas entity.
     *
     */
    public function deleteAction(Request $request, Facturas $factura)
    {
    
        $form = $this->createDeleteForm($factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($factura);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Facturas was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Facturas');
        }
        
        return $this->redirectToRoute('facturas');
    }
    
    /**
     * Creates a form to delete a Facturas entity.
     *
     * @param Facturas $factura The Facturas entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Facturas $factura)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('facturas_delete', array('id' => $factura->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Facturas by id
     *
     */
    public function deleteByIdAction(Facturas $factura){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($factura);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Facturas was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Facturas');
        }

        return $this->redirect($this->generateUrl('facturas'));

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
                $repository = $em->getRepository('BackendBundle:Facturas');

                foreach ($ids as $id) {
                    $factura = $repository->find($id);
                    $em->remove($factura);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'facturas was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the facturas ');
            }
        }

        return $this->redirect($this->generateUrl('facturas'));
    }
    

}
