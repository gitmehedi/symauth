<?php

namespace SystemUsersBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SystemUsersBundle\Entity\Resources;
use SystemUsersBundle\Form\ResourcesType;

/**
 * Resources controller.
 *
 */
class ResourcesController extends Controller
{

    /**
     * Lists all Resources entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SystemUsersBundle:Resources')->findAll();

        return $this->render('SystemUsersBundle:Resources:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Resources entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Resources();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('resources_show', array('id' => $entity->getId())));
        }

        return $this->render('SystemUsersBundle:Resources:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Resources entity.
     *
     * @param Resources $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Resources $entity)
    {
        $form = $this->createForm(new ResourcesType(), $entity, array(
            'action' => $this->generateUrl('resources_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Resources entity.
     *
     */
    public function newAction()
    {
        $entity = new Resources();
        $form   = $this->createCreateForm($entity);

        return $this->render('SystemUsersBundle:Resources:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Resources entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SystemUsersBundle:Resources')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Resources entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SystemUsersBundle:Resources:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Resources entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SystemUsersBundle:Resources')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Resources entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SystemUsersBundle:Resources:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Resources entity.
    *
    * @param Resources $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Resources $entity)
    {
        $form = $this->createForm(new ResourcesType(), $entity, array(
            'action' => $this->generateUrl('resources_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Resources entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SystemUsersBundle:Resources')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Resources entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('resources_edit', array('id' => $id)));
        }

        return $this->render('SystemUsersBundle:Resources:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Resources entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SystemUsersBundle:Resources')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Resources entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('resources'));
    }

    /**
     * Creates a form to delete a Resources entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('resources_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
