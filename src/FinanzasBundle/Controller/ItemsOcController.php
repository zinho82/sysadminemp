<?php

namespace FinanzasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\ItemsOc;

/**
 * ItemsOc controller.
 *
 */
class ItemsOcController extends Controller
{
    /**
     * Lists all ItemsOc entities.
     *
     */
    public function indexAction(Request $request,$id=null)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:ItemsOc')->createQueryBuilder('e')
                ->where("e.ordenescompra=$id");

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($itemsOcs, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('FinanzasBundle:itemsoc:index.html.twig', array(
            'itemsOcs' => $itemsOcs,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,
            'idoc'  =>  $id,

        ));
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, Request $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm('FinanzasBundle\Form\ItemsOcFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('ItemsOcControllerFilter');
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
                $session->set('ItemsOcControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ItemsOcControllerFilter')) {
                $filterData = $session->get('ItemsOcControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('FinanzasBundle\Form\ItemsOcFilterType', $filterData);
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
            return $me->generateUrl('itemsoc', $requestParams);
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
     * Displays a form to create a new ItemsOc entity.
     *
     */
    public function newAction(Request $request,$id=null)
    {
    
        $itemsOc = new ItemsOc();
        $form   = $this->createForm('FinanzasBundle\Form\ItemsOcType', $itemsOc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $oc= $em->getRepository("BackendBundle:Ordenescompra")->find($id);
            $itemsOc->setOrdenescompra($oc);
            $em->persist($itemsOc);
            $em->flush();
            
            $editLink = $this->generateUrl('itemsoc_edit', array('id' => $itemsOc->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New itemsOc was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? "ordenescompra" : "itemsoc";
            echo $nextAction;
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('FinanzasBundle:itemsoc:new.html.twig', array(
            'itemsOc' => $itemsOc,
            'form'   => $form->createView(),
            'idoc'  =>$id,
        ));
    }
    

    /**
     * Finds and displays a ItemsOc entity.
     *
     */
    public function showAction(ItemsOc $itemsOc)
    {
        $deleteForm = $this->createDeleteForm($itemsOc);
        return $this->render('FinanzasBundle:itemsoc:show.html.twig', array(
            'itemsOc' => $itemsOc,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing ItemsOc entity.
     *
     */
    public function editAction(Request $request, ItemsOc $itemsOc)
    {
        $deleteForm = $this->createDeleteForm($itemsOc);
        $editForm = $this->createForm('FinanzasBundle\Form\ItemsOcType', $itemsOc);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($itemsOc);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('itemsoc_edit', array('id' => $itemsOc->getId()));
        }
        return $this->render('FinanzasBundle:itemsoc:edit.html.twig', array(
            'itemsOc' => $itemsOc,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a ItemsOc entity.
     *
     */
    public function deleteAction(Request $request, ItemsOc $itemsOc)
    {
    
        $form = $this->createDeleteForm($itemsOc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($itemsOc);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The ItemsOc was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the ItemsOc');
        }
        
        return $this->redirectToRoute('itemsoc');
    }
    
    /**
     * Creates a form to delete a ItemsOc entity.
     *
     * @param ItemsOc $itemsOc The ItemsOc entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ItemsOc $itemsOc)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('itemsoc_delete', array('id' => $itemsOc->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete ItemsOc by id
     *
     */
    public function deleteByIdAction(ItemsOc $itemsOc){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($itemsOc);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The ItemsOc was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the ItemsOc');
        }

        return $this->redirect($this->generateUrl('itemsoc'));

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
                $repository = $em->getRepository('BackendBundle:ItemsOc');

                foreach ($ids as $id) {
                    $itemsOc = $repository->find($id);
                    $em->remove($itemsOc);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'itemsOcs was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the itemsOcs ');
            }
        }

        return $this->redirect($this->generateUrl('itemsoc'));
    }
    

}
