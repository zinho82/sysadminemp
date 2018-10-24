<?php

namespace IncidenciasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Incidencia;

/**
 * Incidencia controller.
 *
 */
class IncidenciaController extends Controller
{
    /**
     * Lists all Incidencia entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Incidencia')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($incidencias, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('IncidenciasBundle:incidencia:index.html.twig', array(
            'incidencias' => $incidencias,
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
        $filterForm = $this->createForm('IncidenciasBundle\Form\IncidenciaFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('IncidenciaControllerFilter');
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
                $session->set('IncidenciaControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('IncidenciaControllerFilter')) {
                $filterData = $session->get('IncidenciaControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('IncidenciasBundle\Form\IncidenciaFilterType', $filterData);
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
            return $me->generateUrl('incidencia', $requestParams);
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
     * Displays a form to create a new Incidencia entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $incidencium = new Incidencia();
        $form   = $this->createForm('IncidenciasBundle\Form\IncidenciaType', $incidencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $codinci=date("YmdGis");
            $em = $this->getDoctrine()->getManager();
            $incidencium->setFechaIngreso(new \DateTime);
            $incidencium->setIngresasoPor($this->getUser());
            $incidencium->setNumeroIncidencia($codinci);
            $em->persist($incidencium);
            $em->flush();
            
            $editLink = $this->generateUrl('incidencia_edit', array('id' => $incidencium->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New incidencium was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'incidencia' : 'incidencia_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('IncidenciasBundle:incidencia:new.html.twig', array(
            'incidencium' => $incidencium,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Incidencia entity.
     *
     */
    public function showAction(Incidencia $incidencium)
    {
        $deleteForm = $this->createDeleteForm($incidencium);
        return $this->render('IncidenciasBundle:incidencia:show.html.twig', array(
            'incidencium' => $incidencium,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Incidencia entity.
     *
     */
    public function editAction(Request $request, Incidencia $incidencium)
    {
        $deleteForm = $this->createDeleteForm($incidencium);
        $editForm = $this->createForm('IncidenciasBundle\Form\IncidenciaType', $incidencium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($incidencium);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('incidencia_edit', array('id' => $incidencium->getId()));
        }
        return $this->render('IncidenciasBundle:incidencia:edit.html.twig', array(
            'incidencium' => $incidencium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Incidencia entity.
     *
     */
    public function deleteAction(Request $request, Incidencia $incidencium)
    {
    
        $form = $this->createDeleteForm($incidencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($incidencium);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Incidencia was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Incidencia');
        }
        
        return $this->redirectToRoute('incidencia');
    }
    
    /**
     * Creates a form to delete a Incidencia entity.
     *
     * @param Incidencia $incidencium The Incidencia entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Incidencia $incidencium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('incidencia_delete', array('id' => $incidencium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Incidencia by id
     *
     */
    public function deleteByIdAction(Incidencia $incidencium){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($incidencium);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Incidencia was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Incidencia');
        }

        return $this->redirect($this->generateUrl('incidencia'));

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
                $repository = $em->getRepository('BackendBundle:Incidencia');

                foreach ($ids as $id) {
                    $incidencium = $repository->find($id);
                    $em->remove($incidencium);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'incidencias was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the incidencias ');
            }
        }

        return $this->redirect($this->generateUrl('incidencia'));
    }
    

}
