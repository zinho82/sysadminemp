<?php

namespace BancoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Registropago;

/**
 * Registropago controller.
 *
 */
class RegistropagoController extends Controller
{
    /**
     * Lists all Registropago entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Registropago')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($registropagos, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('BancoBundle:registropago:index.html.twig', array(
            'registropagos' => $registropagos,
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
        $filterForm = $this->createForm('BancoBundle\Form\RegistropagoFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('RegistropagoControllerFilter');
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
                $session->set('RegistropagoControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('RegistropagoControllerFilter')) {
                $filterData = $session->get('RegistropagoControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('BancoBundle\Form\RegistropagoFilterType', $filterData);
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
            return $me->generateUrl('registropago', $requestParams);
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
     * Displays a form to create a new Registropago entity.
     *
     */
    public function newAction(Request $request,$factura=null)
    {
    
        $registropago = new Registropago();
        $form   = $this->createForm('BancoBundle\Form\RegistropagoType', $registropago);
        $form->handleRequest($request);
echo "factura: ".$factura;
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $facturas=$em->getRepository("BAckendBundle:Facturas")->find($factura);
            $registropago->setFechaPago(new \DateTime);
            $registropago->setFactura($facturas);
            $em->persist($registropago);
           if( $em->flush()==null){
               $this->get('session')->getFlashBag()->add('success', "<>New registropago was created successfully.</a>" );
           }
            
            $editLink = $this->generateUrl('registropago_edit', array('id' => $registropago->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New registropago was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'registropago' : 'registropago_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('BancoBundle:registropago:new_modal.html.twig', array(
            'registropago' => $registropago,
            'form'   => $form->createView(),
            'factura'   =>  $factura,
        ));
    }
    

    /**
     * Finds and displays a Registropago entity.
     *
     */
    public function showAction(Registropago $registropago)
    {
        $deleteForm = $this->createDeleteForm($registropago);
        return $this->render('BancoBundle:registropago:show.html.twig', array(
            'registropago' => $registropago,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Registropago entity.
     *
     */
    public function editAction(Request $request, Registropago $registropago)
    {
        $deleteForm = $this->createDeleteForm($registropago);
        $editForm = $this->createForm('BancoBundle\Form\RegistropagoType', $registropago);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($registropago);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('registropago_edit', array('id' => $registropago->getId()));
        }
        return $this->render('BancoBundle:registropago:edit.html.twig', array(
            'registropago' => $registropago,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Registropago entity.
     *
     */
    public function deleteAction(Request $request, Registropago $registropago)
    {
    
        $form = $this->createDeleteForm($registropago);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($registropago);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Registropago was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Registropago');
        }
        
        return $this->redirectToRoute('registropago');
    }
    
    /**
     * Creates a form to delete a Registropago entity.
     *
     * @param Registropago $registropago The Registropago entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Registropago $registropago)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('registropago_delete', array('id' => $registropago->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Registropago by id
     *
     */
    public function deleteByIdAction(Registropago $registropago){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($registropago);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Registropago was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Registropago');
        }

        return $this->redirect($this->generateUrl('registropago'));

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
                $repository = $em->getRepository('BackendBundle:Registropago');

                foreach ($ids as $id) {
                    $registropago = $repository->find($id);
                    $em->remove($registropago);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'registropagos was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the registropagos ');
            }
        }

        return $this->redirect($this->generateUrl('registropago'));
    }
    

}
