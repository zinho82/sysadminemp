<?php

namespace FinanzasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Autorizaciones;

/**
 * Autorizaciones controller.
 *
 */
class AutorizacionesController extends Controller
{
    /**
     * Lists all Autorizaciones entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Autorizaciones')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($autorizaciones, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('FinanzasBundle:autorizaciones:index.html.twig', array(
            'autorizaciones' => $autorizaciones,
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
        $filterForm = $this->createForm('FinanzasBundle\Form\AutorizacionesFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('AutorizacionesControllerFilter');
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
                $session->set('AutorizacionesControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('AutorizacionesControllerFilter')) {
                $filterData = $session->get('AutorizacionesControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('FinanzasBundle\Form\AutorizacionesFilterType', $filterData);
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
            return $me->generateUrl('autorizaciones', $requestParams);
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
     * Displays a form to create a new Autorizaciones entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $autorizacione = new Autorizaciones();
        $form   = $this->createForm('FinanzasBundle\Form\AutorizacionesType', $autorizacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($autorizacione);
            $em->flush();
            
            $editLink = $this->generateUrl('autorizaciones_edit', array('id' => $autorizacione->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New autorizacione was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'autorizaciones' : 'autorizaciones_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('FinanzasBundle:autorizaciones:new.html.twig', array(
            'autorizacione' => $autorizacione,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Autorizaciones entity.
     *
     */
    public function showAction(Autorizaciones $autorizacione)
    {
        $deleteForm = $this->createDeleteForm($autorizacione);
        return $this->render('FinanzasBundle:autorizaciones:show.html.twig', array(
            'autorizacione' => $autorizacione,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Autorizaciones entity.
     *
     */
    public function editAction(Request $request, Autorizaciones $autorizacione)
    {
        $deleteForm = $this->createDeleteForm($autorizacione);
        $editForm = $this->createForm('FinanzasBundle\Form\AutorizacionesType', $autorizacione);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($autorizacione);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('autorizaciones_edit', array('id' => $autorizacione->getId()));
        }
        return $this->render('FinanzasBundle:autorizaciones:edit.html.twig', array(
            'autorizacione' => $autorizacione,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Autorizaciones entity.
     *
     */
    public function deleteAction(Request $request, Autorizaciones $autorizacione)
    {
    
        $form = $this->createDeleteForm($autorizacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($autorizacione);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Autorizaciones was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Autorizaciones');
        }
        
        return $this->redirectToRoute('autorizaciones');
    }
    
    /**
     * Creates a form to delete a Autorizaciones entity.
     *
     * @param Autorizaciones $autorizacione The Autorizaciones entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Autorizaciones $autorizacione)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('autorizaciones_delete', array('id' => $autorizacione->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Autorizaciones by id
     *
     */
    public function deleteByIdAction(Autorizaciones $autorizacione){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($autorizacione);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Autorizaciones was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Autorizaciones');
        }

        return $this->redirect($this->generateUrl('autorizaciones'));

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
                $repository = $em->getRepository('BackendBundle:Autorizaciones');

                foreach ($ids as $id) {
                    $autorizacione = $repository->find($id);
                    $em->remove($autorizacione);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'autorizaciones was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the autorizaciones ');
            }
        }

        return $this->redirect($this->generateUrl('autorizaciones'));
    }
    

}
