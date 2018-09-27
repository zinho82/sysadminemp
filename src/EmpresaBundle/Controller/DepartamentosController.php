<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Departamentos;

/**
 * Departamentos controller.
 *
 */
class DepartamentosController extends Controller
{
    /**
     * Lists all Departamentos entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Departamentos')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($departamentos, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('departamentos/index.html.twig', array(
            'departamentos' => $departamentos,
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
        $filterForm = $this->createForm('BackendBundle\Form\DepartamentosFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('DepartamentosControllerFilter');
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
                $session->set('DepartamentosControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('DepartamentosControllerFilter')) {
                $filterData = $session->get('DepartamentosControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('BackendBundle\Form\DepartamentosFilterType', $filterData);
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
            return $me->generateUrl('departamentos', $requestParams);
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
     * Displays a form to create a new Departamentos entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $departamento = new Departamentos();
        $form   = $this->createForm('BackendBundle\Form\DepartamentosType', $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($departamento);
            $em->flush();
            
            $editLink = $this->generateUrl('departamentos_edit', array('id' => $departamento->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New departamento was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'departamentos' : 'departamentos_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('departamentos/new.html.twig', array(
            'departamento' => $departamento,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Departamentos entity.
     *
     */
    public function showAction(Departamentos $departamento)
    {
        $deleteForm = $this->createDeleteForm($departamento);
        return $this->render('departamentos/show.html.twig', array(
            'departamento' => $departamento,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Departamentos entity.
     *
     */
    public function editAction(Request $request, Departamentos $departamento)
    {
        $deleteForm = $this->createDeleteForm($departamento);
        $editForm = $this->createForm('BackendBundle\Form\DepartamentosType', $departamento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($departamento);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('departamentos_edit', array('id' => $departamento->getId()));
        }
        return $this->render('departamentos/edit.html.twig', array(
            'departamento' => $departamento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Departamentos entity.
     *
     */
    public function deleteAction(Request $request, Departamentos $departamento)
    {
    
        $form = $this->createDeleteForm($departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($departamento);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Departamentos was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Departamentos');
        }
        
        return $this->redirectToRoute('departamentos');
    }
    
    /**
     * Creates a form to delete a Departamentos entity.
     *
     * @param Departamentos $departamento The Departamentos entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Departamentos $departamento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('departamentos_delete', array('id' => $departamento->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Departamentos by id
     *
     */
    public function deleteByIdAction(Departamentos $departamento){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($departamento);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Departamentos was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Departamentos');
        }

        return $this->redirect($this->generateUrl('departamentos'));

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
                $repository = $em->getRepository('BackendBundle:Departamentos');

                foreach ($ids as $id) {
                    $departamento = $repository->find($id);
                    $em->remove($departamento);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'departamentos was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the departamentos ');
            }
        }

        return $this->redirect($this->generateUrl('departamentos'));
    }
    

}
