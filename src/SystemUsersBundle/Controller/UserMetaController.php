<?php

namespace SystemUsersBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SystemUsersBundle\Entity\UserMeta;
use SystemUsersBundle\Form\UserMetaType;

/**
 * UserMeta controller.
 *
 */
class UserMetaController extends Controller
{

    /**
     * Lists all UserMeta entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SporshoUserBundle:UserMeta')->findAll();

        return $this->render('SporshoUserBundle:UserMeta:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new UserMeta entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new UserMeta();
        $form   = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('usermeta_show', array('id' => $entity->getId())));
        }

        return $this->render('SporshoUserBundle:UserMeta:new.html.twig', array(
                    'entity' => $entity,
                    'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a UserMeta entity.
     *
     * @param UserMeta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(UserMeta $entity)
    {
        $form = $this->createForm(new UserMetaType(), $entity, array(
            'action' => $this->generateUrl('usermeta_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new UserMeta entity.
     *
     */
    public function newAction()
    {
        $entity = new UserMeta();
        $form   = $this->createCreateForm($entity);

        return $this->render('SporshoUserBundle:UserMeta:new.html.twig', array(
                    'entity' => $entity,
                    'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserMeta entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SporshoUserBundle:UserMeta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserMeta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SporshoUserBundle:UserMeta:show.html.twig', array(
                    'entity'      => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing UserMeta entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SporshoUserBundle:UserMeta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserMeta entity.');
        }

        $editForm   = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SporshoUserBundle:UserMeta:edit.html.twig', array(
                    'entity'      => $entity,
                    'edit_form'   => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a UserMeta entity.
     *
     * @param UserMeta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(UserMeta $entity)
    {
        $form = $this->createForm(new UserMetaType(), $entity, array(
            'action' => $this->generateUrl('usermeta_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing UserMeta entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SporshoUserBundle:UserMeta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserMeta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('usermeta_edit', array('id' => $id)));
        }

        return $this->render('SporshoUserBundle:UserMeta:edit.html.twig', array(
                    'entity'      => $entity,
                    'edit_form'   => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserMeta entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SporshoUserBundle:UserMeta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find UserMeta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('usermeta'));
    }

    /**
     * Creates a form to delete a UserMeta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('usermeta_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

}
