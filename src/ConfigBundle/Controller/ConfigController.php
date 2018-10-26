<?php

namespace ConfigBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use BackendBundle\Entity\Config;

/**
 * Config controller.
 *
 */
class ConfigController extends Controller
{
    /**
     * Lists all Config entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Config')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($configs, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('ConfigBundle:config:index.html.twig', array(
            'configs' => $configs,
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
        $filterForm = $this->createForm('ConfigBundle\Form\ConfigFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('ConfigControllerFilter');
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
                $session->set('ConfigControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ConfigControllerFilter')) {
                $filterData = $session->get('ConfigControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('ConfigBundle\Form\ConfigFilterType', $filterData);
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
            return $me->generateUrl('config', $requestParams);
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
     * Displays a form to create a new Config entity.
     *
     */
    public function newAction(Request $request)
    {
    
        $config = new Config();
        $form   = $this->createForm('ConfigBundle\Form\ConfigType', $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($config);
            $em->flush();
            
            $editLink = $this->generateUrl('config_edit', array('id' => $config->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New config was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'config' : 'config_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('ConfigBundle:config:new.html.twig', array(
            'config' => $config,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Config entity.
     *
     */
    public function showAction(Config $config)
    {
        $deleteForm = $this->createDeleteForm($config);
        return $this->render('ConfigBundle:config:show.html.twig', array(
            'config' => $config,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Config entity.
     *
     */
    public function editAction(Request $request, Config $config)
    {
        $deleteForm = $this->createDeleteForm($config);
        $editForm = $this->createForm('ConfigBundle\Form\ConfigType', $config);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($config);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('config_edit', array('id' => $config->getId()));
        }
        return $this->render('ConfigBundle:config:edit.html.twig', array(
            'config' => $config,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Config entity.
     *
     */
    public function deleteAction(Request $request, Config $config)
    {
    
        $form = $this->createDeleteForm($config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($config);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Config was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Config');
        }
        
        return $this->redirectToRoute('config');
    }
    
    /**
     * Creates a form to delete a Config entity.
     *
     * @param Config $config The Config entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Config $config)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('config_delete', array('id' => $config->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Config by id
     *
     */
    public function deleteByIdAction(Config $config){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($config);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Config was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Config');
        }

        return $this->redirect($this->generateUrl('config'));

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
                $repository = $em->getRepository('BackendBundle:Config');

                foreach ($ids as $id) {
                    $config = $repository->find($id);
                    $em->remove($config);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'configs was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the configs ');
            }
        }

        return $this->redirect($this->generateUrl('config'));
    }
    

}
