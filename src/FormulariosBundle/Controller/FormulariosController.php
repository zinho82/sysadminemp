<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Formularios;

/**
 * Formularios controller.
 *
 */
class FormulariosController extends Controller
{
    /**
     * Lists all Formularios entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Formularios')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($formularios, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('formularios/index.html.twig', array(
            'formularios' => $formularios,
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
        $filterForm = $this->createForm('BackendBundle\Form\FormulariosFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('FormulariosControllerFilter');
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
                $session->set('FormulariosControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('FormulariosControllerFilter')) {
                $filterData = $session->get('FormulariosControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('BackendBundle\Form\FormulariosFilterType', $filterData);
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
            return $me->generateUrl('formularios', $requestParams);
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
     * Displays a form to create a new Formularios entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $formulario = new Formularios();
        $form   = $this->createForm('BackendBundle\Form\FormulariosType', $formulario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formulario);
            $em->flush();
            
            $editLink = $this->generateUrl('formularios_edit', array('id' => $formulario->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New formulario was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'formularios' : 'formularios_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('formularios/new.html.twig', array(
            'formulario' => $formulario,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Formularios entity.
     *
     */
    public function showAction(Formularios $formulario)
    {
        $deleteForm = $this->createDeleteForm($formulario);
        return $this->render('formularios/show.html.twig', array(
            'formulario' => $formulario,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Formularios entity.
     *
     */
    public function editAction(Request $request, Formularios $formulario)
    {
        $deleteForm = $this->createDeleteForm($formulario);
        $editForm = $this->createForm('BackendBundle\Form\FormulariosType', $formulario);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formulario);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('formularios_edit', array('id' => $formulario->getId()));
        }
        return $this->render('formularios/edit.html.twig', array(
            'formulario' => $formulario,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Formularios entity.
     *
     */
    public function deleteAction(Request $request, Formularios $formulario)
    {
    
        $form = $this->createDeleteForm($formulario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($formulario);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Formularios was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Formularios');
        }
        
        return $this->redirectToRoute('formularios');
    }
    
    /**
     * Creates a form to delete a Formularios entity.
     *
     * @param Formularios $formulario The Formularios entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Formularios $formulario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('formularios_delete', array('id' => $formulario->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Formularios by id
     *
     */
    public function deleteByIdAction(Formularios $formulario){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($formulario);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Formularios was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Formularios');
        }

        return $this->redirect($this->generateUrl('formularios'));

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
                $repository = $em->getRepository('BackendBundle:Formularios');

                foreach ($ids as $id) {
                    $formulario = $repository->find($id);
                    $em->remove($formulario);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'formularios was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the formularios ');
            }
        }

        return $this->redirect($this->generateUrl('formularios'));
    }
    

}
