<?php

namespace DocumentosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;
use BackendBundle\Entity\Documentos;

/**
 * Documentos controller.
 *
 */
class DocumentosController extends Controller {

    /**
     * Lists all Documentos entities.
     *
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('BackendBundle:Documentos')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($documentos, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('DocumentosBundle:documentos:index.html.twig', array(
                    'documentos' => $documentos,
                    'pagerHtml' => $pagerHtml,
                    'filterForm' => $filterForm->createView(),
                    'totalOfRecordsString' => $totalOfRecordsString,
        ));
    }

    /**
     * Agrega registro segun el depto que existe 
     *
     */
    public function agregarAction(Request $request, $id = null, $seccion = null, $empresa = null) {
 $em = $this->getDoctrine()->getManager();
            $documento = new Documentos();
        $form = $this->createForm('DocumentosBundle\Form\DocumentosType', $documento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
$dempresa=$em->getRepository("BackendBundle:empresa")->find($empresa);
$drh=$em->getRepository("BackendBundle:rrhh")->find($id);
            $file = $form['archivo']->getData();

// Sacamos la extensión del fichero
            $ext = $file->guessExtension();
 
// Le ponemos un nombre al fichero
            $fileName = time() . "." . $ext;
            $file->move("uploads/rrhh", $fileName);
            $documento->setFechaCarga(new \DateTime);
            $documento->setCargadoPor($this->getUser());
            $documento->setArchivo($fileName);
            $documento->setRrhh($drh);
            $documento->setEmpresa($dempresa);
            $em->persist($documento);
            $em->flush();
 
            $editLink = $this->generateUrl('documentos_edit', array('id' => $documento->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New documento was created successfully.</a>");

            $nextAction = $request->get('submit') == 'save' ? 'documentos' : 'documentos_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('DocumentosBundle:documentos:new.html.twig', array(
                    'documento' => $documento,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Create filter form and process filter request.
     *
     */
    protected function filter($queryBuilder, Request $request) {
        $session = $request->getSession();
        $filterForm = $this->createForm('DocumentosBundle\Form\DocumentosFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('DocumentosControllerFilter');
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
                $session->set('DocumentosControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('DocumentosControllerFilter')) {
                $filterData = $session->get('DocumentosControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('DocumentosBundle\Form\DocumentosFilterType', $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }

    /**
     * Get results from paginator and get paginator view.
     *
     */
    protected function paginator($queryBuilder, Request $request) {
        //sorting
        $sortCol = $queryBuilder->getRootAlias() . '.' . $request->get('pcg_sort_col', 'id');
        $queryBuilder->orderBy($sortCol, $request->get('pcg_sort_order', 'desc'));
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($request->get('pcg_show', 10));

        try {
            $pagerfanta->setCurrentPage($request->get('pcg_page', 1));
        } catch (\Pagerfanta\Exception\OutOfRangeCurrentPageException $ex) {
            $pagerfanta->setCurrentPage(1);
        }

        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me, $request) {
            $requestParams = $request->query->all();
            $requestParams['pcg_page'] = $page;
            return $me->generateUrl('documentos', $requestParams);
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
     * Displays a form to create a new Documentos entity.
     *
     */
    public function newAction(Request $request) {

        $documento = new Documentos();
        $form = $this->createForm('DocumentosBundle\Form\DocumentosType', $documento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documento);
            $em->flush();

            $editLink = $this->generateUrl('documentos_edit', array('id' => $documento->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New documento was created successfully.</a>");

            $nextAction = $request->get('submit') == 'save' ? 'documentos' : 'documentos_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('DocumentosBundle:documentos:new.html.twig', array(
                    'documento' => $documento,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Documentos entity.
     *
     */
    public function showAction(Documentos $documento) {
        $deleteForm = $this->createDeleteForm($documento);
        return $this->render('DocumentosBundle:documentos:show.html.twig', array(
                    'documento' => $documento,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Documentos entity.
     *
     */
    public function editAction(Request $request, Documentos $documento) {
        $deleteForm = $this->createDeleteForm($documento);
        $editForm = $this->createForm('DocumentosBundle\Form\DocumentosType', $documento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documento);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('documentos_edit', array('id' => $documento->getId()));
        }
        return $this->render('DocumentosBundle:documentos:edit.html.twig', array(
                    'documento' => $documento,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Documentos entity.
     *
     */
    public function deleteAction(Request $request, Documentos $documento) {

        $form = $this->createDeleteForm($documento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($documento);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Documentos was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Documentos');
        }

        return $this->redirectToRoute('documentos');
    }

    /**
     * Creates a form to delete a Documentos entity.
     *
     * @param Documentos $documento The Documentos entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Documentos $documento) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('documentos_delete', array('id' => $documento->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Delete Documentos by id
     *
     */
    public function deleteByIdAction(Documentos $documento) {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($documento);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Documentos was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Documentos');
        }

        return $this->redirect($this->generateUrl('documentos'));
    }

    /**
     * Bulk Action
     */
    public function bulkAction(Request $request) {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('BackendBundle:Documentos');

                foreach ($ids as $id) {
                    $documento = $repository->find($id);
                    $em->remove($documento);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'documentos was deleted successfully!');
            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the documentos ');
            }
        }

        return $this->redirect($this->generateUrl('documentos'));
    }

}
