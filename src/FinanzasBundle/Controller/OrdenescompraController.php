<?php

namespace FinanzasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Ordenescompra;
use BackendBundle\Entity\ItemsOc;

/**
 * Ordenescompra controller.
 *
 */
class OrdenescompraController extends Controller
{
    /**
     * Lists all Ordenescompra entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Ordenescompra')->createQueryBuilder('e');
$query = 'update ordenescompra oc
set oc.subtotal=(select sum(i.valor * i.cantidad) as total from items_oc i where oc.id=i.ordenescompra)';
        $stmt = $em->getConnection()->prepare($query);
            $stmt->execute();
             $query = 'update ordenescompra oc set oc.iva=(oc.subtotal*(19/100))';
        $stmt= $em->getConnection()->prepare($query);
        $stmt->execute();
        $query = 'update ordenescompra oc set oc.total=(oc.subtotal+oc.iva)';
        $stmt= $em->getConnection()->prepare($query);
        $stmt->execute();
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($ordenescompras, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('FinanzasBundle:ordenescompra:index.html.twig', array(
            'ordenescompras' => $ordenescompras,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,

        ));
    }
      public function indexAutorizacionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Ordenescompra')->createQueryBuilder('e');
$query = 'update ordenescompra oc
set oc.subtotal=(select sum(i.valor * i.cantidad) as total from items_oc i where oc.id=i.ordenescompra)';
        $stmt = $em->getConnection()->prepare($query);
            $stmt->execute();
             $query = 'update ordenescompra oc set oc.iva=(oc.subtotal*(19/100))';
        $stmt= $em->getConnection()->prepare($query);
        $stmt->execute();
        $query = 'update ordenescompra oc set oc.total=(oc.subtotal+oc.iva)';
        $stmt= $em->getConnection()->prepare($query);
        $stmt->execute();
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($ordenescompras, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('FinanzasBundle:autorizaciones:index_autorizacion.html.twig', array(
            'ordenescompras' => $ordenescompras,
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
        $filterForm = $this->createForm('FinanzasBundle\Form\OrdenescompraFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('OrdenescompraControllerFilter');
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
                $session->set('OrdenescompraControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('OrdenescompraControllerFilter')) {
                $filterData = $session->get('OrdenescompraControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('FinanzasBundle\Form\OrdenescompraFilterType', $filterData);
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
            return $me->generateUrl('ordenescompra', $requestParams);
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
     * Displays a form to create a new Ordenescompra entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $ordenescompra = new Ordenescompra();
        $form   = $this->createForm('FinanzasBundle\Form\OrdenescompraType', $ordenescompra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $ordenescompra->setSolicitadoPor($this->getUser());
            $em->persist($ordenescompra);
            
            $em->flush();
            
            $editLink = $this->generateUrl('ordenescompra_edit', array('id' => $ordenescompra->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New ordenescompra was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'ordenescompra' : 'ordenescompra_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('FinanzasBundle:ordenescompra:new.html.twig', array(
            'ordenescompra' => $ordenescompra,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Ordenescompra entity.
     *
     */
    public function showAction(Ordenescompra $ordenescompra)
    {
        $deleteForm = $this->createDeleteForm($ordenescompra);
        return $this->render('FinanzasBundle:ordenescompra:show.html.twig', array(
            'ordenescompra' => $ordenescompra,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Ordenescompra entity.
     *
     */
    public function editAction(Request $request, Ordenescompra $ordenescompra)
    {
        $deleteForm = $this->createDeleteForm($ordenescompra);
        $editForm = $this->createForm('FinanzasBundle\Form\OrdenescompraType', $ordenescompra);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ordenescompra);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('ordenescompra_edit', array('id' => $ordenescompra->getId()));
        }
        return $this->render('FinanzasBundle:ordenescompra:edit.html.twig', array(
            'ordenescompra' => $ordenescompra,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Ordenescompra entity.
     *
     */
    public function deleteAction(Request $request, Ordenescompra $ordenescompra)
    {
    
        $form = $this->createDeleteForm($ordenescompra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ordenescompra);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Ordenescompra was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Ordenescompra');
        }
        
        return $this->redirectToRoute('ordenescompra');
    }
    
    /**
     * Creates a form to delete a Ordenescompra entity.
     *
     * @param Ordenescompra $ordenescompra The Ordenescompra entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ordenescompra $ordenescompra)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ordenescompra_delete', array('id' => $ordenescompra->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Ordenescompra by id
     *
     */
    public function deleteByIdAction(Ordenescompra $ordenescompra){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($ordenescompra);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Ordenescompra was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Ordenescompra');
        }

        return $this->redirect($this->generateUrl('ordenescompra'));

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
                $repository = $em->getRepository('BackendBundle:Ordenescompra');

                foreach ($ids as $id) {
                    $ordenescompra = $repository->find($id);
                    $em->remove($ordenescompra);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'ordenescompras was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the ordenescompras ');
            }
        }

        return $this->redirect($this->generateUrl('ordenescompra'));
    }
    

}
