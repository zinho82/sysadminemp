<?php

namespace FinanzasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Contactos;

/**
 * Contactos controller.
 *
 */
class ContactosController extends Controller
{
    /**
     * Lists all Contactos entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Contactos')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($contactos, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('FinanzasBundle:contactos:index.html.twig', array(
            'contactos' => $contactos,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,

        ));
    }
    /**
     * Lists all Contactos entities.
     *
     */
    public function indexEmpresaAction(Request $request,$id=null)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Contactos')->createQueryBuilder('e')
                ->where("e.proveedoresClientes=$id");

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($contactos, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('FinanzasBundle:contactos:index.html.twig', array(
            'contactos' => $contactos,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,
            'idproveedor'   => $id,

        ));
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, Request $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm('FinanzasBundle\Form\ContactosFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('ContactosControllerFilter');
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
                $session->set('ContactosControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ContactosControllerFilter')) {
                $filterData = $session->get('ContactosControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('FinanzasBundle\Form\ContactosFilterType', $filterData);
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
            return $me->generateUrl('contactos', $requestParams);
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
     * Displays a form to create a new Contactos entity.
     *
     */
    public function newAction(Request $request,$idproveedor=null)
    {
        echo ' prob '.$idproveedor;
        $contacto = new Contactos();
        $form   = $this->createForm('FinanzasBundle\Form\ContactosType', $contacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $prov=$em->getRepository("BackendBundle:ProveedoresClientes")->find($idproveedor);
            $contacto->setProveedoresClientes($prov);
            $em->persist($contacto);
            $em->flush();
            
            $editLink = $this->generateUrl('contactos_edit', array('idproveedor' => $idproveedor));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New contacto was created successfully.</a>" );
            $nextAction=  $request->get('submit') == 'save' ? 'contactos' :"contactos_new";
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('FinanzasBundle:contactos:new.html.twig', array(
            'contacto' => $contacto,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Contactos entity.
     *
     */
    public function showAction(Contactos $contacto)
    {
        $deleteForm = $this->createDeleteForm($contacto);
        return $this->render('FinanzasBundle:contactos:show.html.twig', array(
            'contacto' => $contacto,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Contactos entity.
     *
     */
    public function editAction(Request $request, Contactos $contacto)
    {
        $deleteForm = $this->createDeleteForm($contacto);
        $editForm = $this->createForm('FinanzasBundle\Form\ContactosType', $contacto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contacto);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('contactos_edit', array('id' => $contacto->getId()));
        }
        return $this->render('FinanzasBundle:contactos:edit.html.twig', array(
            'contacto' => $contacto,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Contactos entity.
     *
     */
    public function deleteAction(Request $request, Contactos $contacto)
    {
    
        $form = $this->createDeleteForm($contacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contacto);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Contactos was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Contactos');
        }
        
        return $this->redirectToRoute('contactos');
    }
    
    /**
     * Creates a form to delete a Contactos entity.
     *
     * @param Contactos $contacto The Contactos entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contactos $contacto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contactos_delete', array('id' => $contacto->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Contactos by id
     *
     */
    public function deleteByIdAction(Contactos $contacto){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($contacto);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Contactos was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Contactos');
        }

        return $this->redirect($this->generateUrl('contactos'));

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
                $repository = $em->getRepository('BackendBundle:Contactos');

                foreach ($ids as $id) {
                    $contacto = $repository->find($id);
                    $em->remove($contacto);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'contactos was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the contactos ');
            }
        }

        return $this->redirect($this->generateUrl('contactos'));
    }
    

}
