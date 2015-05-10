<?php

namespace SystemUsersBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SystemUsersBundle\Controller\AppController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SystemUsersBundle\Entity\Users;
use SystemUsersBundle\Form\UsersType;

/**
 * Users controller.
 *
 */
class UsersController extends AppController
{

    /**
     * Lists all Users entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SystemUsersBundle:Users')->findAll();

        return $this->render('SystemUsersBundle:Users:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Users entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Users();
echo $this->container->getParameter('name'); die();
        $form = $this->createForm(new UsersType(), $entity, $this->formAttr());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setPassword($this->encodePassword($entity, $entity->getPlainpassword()))
//                    ->setSlug($this->get('cocur_slugify')->slugify($entity->getName()))
                    ->setRoles(array('ROLE_USER'))
                    ->setAppStatus(1)
                    ->setIsActive(0)
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('users_show', array('id' => $entity->getId())));
        }

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }


//        $error = $this->getErrorMessages($form);
//        foreach ($error as $key => $errors) {
//            $errors[0] = ($key == "name") ? $errors[0] : 'new langulage';
//            ValidationController::debug($errors[0]);
//        }




//        die();
        return $this->render('SystemUsersBundle:Users:new.html.twig', array(
                    'entity' => $entity,
                    'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Users entity.
     *
     * @param Users $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Users $entity)
    {
        $form = $this->createForm(new UsersType(), $entity, array(
            'action' => $this->generateUrl('users_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Users entity.
     *
     */
    public function newAction()
    {
        $entity = new Users();
        $form   = $this->createForm(new UsersType(), $entity, $this->formAttr());


        return $this->render('SystemUsersBundle:Users:new.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Users entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SystemUsersBundle:Users')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Users entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SystemUsersBundle:Users:show.html.twig', array(
                    'entity'      => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Users entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SystemUsersBundle:Users')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Users entity.');
        }

        $editForm   = $this->createForm(new UsersType(), $entity, $this->formAttr($id));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SystemUsersBundle:Users:edit.html.twig', array(
                    'entity'      => $entity,
                    'form'        => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Users entity.
     *
     * @param Users $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Users $entity)
    {
        $form = $this->createForm(new UsersType(), $entity, array(
            'action' => $this->generateUrl('users_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Users entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SystemUsersBundle:Users')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Users entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('users_edit', array('id' => $id)));
        }

        return $this->render('SystemUsersBundle:Users:edit.html.twig', array(
                    'entity'      => $entity,
                    'edit_form'   => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Users entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SystemUsersBundle:Users')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Users entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('users'));
    }

    /**
     * Creates a form to delete a Users entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('users_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('attr' => array('class' => 'btn btn-primary'), 'label' => 'Delete'))
                        ->getForm();
    }

    public function formAttr($id = false)
    {
        $insert = $id ? $this->generateUrl('users_update', array('id' => $id)) : $this->generateUrl('users_create');

        $userRole = $this->getUser()? : null;
        return array(
            'action' => $insert,
            'method' => 'POST',
            'attr'   => array(
                'role'       => 'form',
                'class'      => 'form-horizontal',
                'id'         => 'signupForm',
                'enctype'    => 'multipart/form-data',
                'novalidate' => ''
            )
        );
    }

    /**
     * Finds and displays a Users entity.
     *
     */
    public function profileAction()
    {
        $logUser = $this->getCurrentUser();
        $id      = $logUser->getId();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SystemUsersBundle:Users')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Users entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SystemUsersBundle:Users:show.html.twig', array(
                    'entity'      => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Encode user password according to encryption methods
     * 
     * @param type $user
     * @param type $plainPassword
     * @return string
     */
    private function encodePassword($user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    private function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

}
