<?php

namespace BancoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\MovimientosBanco;

/**
 * MovimientosBanco controller.
 *
 */
class MovimientosBancoController extends Controller
{
    /**
     * Lists all MovimientosBanco entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:MovimientosBanco')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($movimientosBancos, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('BancoBundle:movimientosbanco:index.html.twig', array(
            'movimientosBancos' => $movimientosBancos,
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
        $filterForm = $this->createForm('BancoBundle\Form\MovimientosBancoFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('MovimientosBancoControllerFilter');
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
                $session->set('MovimientosBancoControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('MovimientosBancoControllerFilter')) {
                $filterData = $session->get('MovimientosBancoControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('BancoBundle\Form\MovimientosBancoFilterType', $filterData);
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
            return $me->generateUrl('movimientosbanco', $requestParams);
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
     * Displays a form to create a new MovimientosBanco entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $movimientosBanco = new MovimientosBanco();
        $form   = $this->createForm('BancoBundle\Form\MovimientosBancoType', $movimientosBanco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movimientosBanco);
            $em->flush();
            
            $editLink = $this->generateUrl('movimientosbanco_edit', array('id' => $movimientosBanco->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New movimientosBanco was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'movimientosbanco' : 'movimientosbanco_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('BancoBundle:movimientosbanco:new.html.twig', array(
            'movimientosBanco' => $movimientosBanco,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a MovimientosBanco entity.
     *
     */
    public function showAction(MovimientosBanco $movimientosBanco)
    {
        $deleteForm = $this->createDeleteForm($movimientosBanco);
        return $this->render('BancoBundle:movimientosbanco:show.html.twig', array(
            'movimientosBanco' => $movimientosBanco,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing MovimientosBanco entity.
     *
     */
    public function editAction(Request $request, MovimientosBanco $movimientosBanco)
    {
        $deleteForm = $this->createDeleteForm($movimientosBanco);
        $editForm = $this->createForm('BancoBundle\Form\MovimientosBancoType', $movimientosBanco);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movimientosBanco);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('movimientosbanco_edit', array('id' => $movimientosBanco->getId()));
        }
        return $this->render('BancoBundle:movimientosbanco:edit.html.twig', array(
            'movimientosBanco' => $movimientosBanco,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a MovimientosBanco entity.
     *
     */
    public function deleteAction(Request $request, MovimientosBanco $movimientosBanco)
    {
    
        $form = $this->createDeleteForm($movimientosBanco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($movimientosBanco);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The MovimientosBanco was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the MovimientosBanco');
        }
        
        return $this->redirectToRoute('movimientosbanco');
    }
    
    /**
     * Creates a form to delete a MovimientosBanco entity.
     *
     * @param MovimientosBanco $movimientosBanco The MovimientosBanco entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MovimientosBanco $movimientosBanco)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('movimientosbanco_delete', array('id' => $movimientosBanco->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete MovimientosBanco by id
     *
     */
    public function deleteByIdAction(MovimientosBanco $movimientosBanco){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($movimientosBanco);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The MovimientosBanco was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the MovimientosBanco');
        }

        return $this->redirect($this->generateUrl('movimientosbanco'));

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
                $repository = $em->getRepository('BackendBundle:MovimientosBanco');

                foreach ($ids as $id) {
                    $movimientosBanco = $repository->find($id);
                    $em->remove($movimientosBanco);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'movimientosBancos was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the movimientosBancos ');
            }
        }

        return $this->redirect($this->generateUrl('movimientosbanco'));
    }
    

}
