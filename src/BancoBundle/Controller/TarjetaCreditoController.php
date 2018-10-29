<?php

namespace BancoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\TarjetaCredito;

/**
 * TarjetaCredito controller.
 *
 */
class TarjetaCreditoController extends Controller
{
    /**
     * Lists all TarjetaCredito entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:TarjetaCredito')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($tarjetaCreditos, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('BancoBundle:tarjetacredito:index.html.twig', array(
            'tarjetaCreditos' => $tarjetaCreditos,
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
        $filterForm = $this->createForm('BancoBundle\Form\TarjetaCreditoFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('TarjetaCreditoControllerFilter');
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
                $session->set('TarjetaCreditoControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('TarjetaCreditoControllerFilter')) {
                $filterData = $session->get('TarjetaCreditoControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('BancoBundle\Form\TarjetaCreditoFilterType', $filterData);
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
            return $me->generateUrl('tarjetacredito', $requestParams);
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
     * Displays a form to create a new TarjetaCredito entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $tarjetaCredito = new TarjetaCredito();
        $form   = $this->createForm('BancoBundle\Form\TarjetaCreditoType', $tarjetaCredito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tarjetaCredito);
            $em->flush();
            
            $editLink = $this->generateUrl('tarjetacredito_edit', array('id' => $tarjetaCredito->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New tarjetaCredito was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'tarjetacredito' : 'tarjetacredito_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('BancoBundle:tarjetacredito:new.html.twig', array(
            'tarjetaCredito' => $tarjetaCredito,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a TarjetaCredito entity.
     *
     */
    public function showAction(TarjetaCredito $tarjetaCredito)
    {
        $deleteForm = $this->createDeleteForm($tarjetaCredito);
        return $this->render('BancoBundle:tarjetacredito:show.html.twig', array(
            'tarjetaCredito' => $tarjetaCredito,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing TarjetaCredito entity.
     *
     */
    public function editAction(Request $request, TarjetaCredito $tarjetaCredito)
    {
        $deleteForm = $this->createDeleteForm($tarjetaCredito);
        $editForm = $this->createForm('BancoBundle\Form\TarjetaCreditoType', $tarjetaCredito);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tarjetaCredito);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('tarjetacredito_edit', array('id' => $tarjetaCredito->getId()));
        }
        return $this->render('BancoBundle:tarjetacredito:edit.html.twig', array(
            'tarjetaCredito' => $tarjetaCredito,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a TarjetaCredito entity.
     *
     */
    public function deleteAction(Request $request, TarjetaCredito $tarjetaCredito)
    {
    
        $form = $this->createDeleteForm($tarjetaCredito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tarjetaCredito);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The TarjetaCredito was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the TarjetaCredito');
        }
        
        return $this->redirectToRoute('tarjetacredito');
    }
    
    /**
     * Creates a form to delete a TarjetaCredito entity.
     *
     * @param TarjetaCredito $tarjetaCredito The TarjetaCredito entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TarjetaCredito $tarjetaCredito)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tarjetacredito_delete', array('id' => $tarjetaCredito->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete TarjetaCredito by id
     *
     */
    public function deleteByIdAction(TarjetaCredito $tarjetaCredito){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($tarjetaCredito);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The TarjetaCredito was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the TarjetaCredito');
        }

        return $this->redirect($this->generateUrl('tarjetacredito'));

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
                $repository = $em->getRepository('BackendBundle:TarjetaCredito');

                foreach ($ids as $id) {
                    $tarjetaCredito = $repository->find($id);
                    $em->remove($tarjetaCredito);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'tarjetaCreditos was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the tarjetaCreditos ');
            }
        }

        return $this->redirect($this->generateUrl('tarjetacredito'));
    }
    

}
