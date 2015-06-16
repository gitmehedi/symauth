<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\DemoBundle\Entity\Task;
use Acme\DemoBundle\Form\TaskType;
use Acme\DemoBundle\Entity\Tag;

/**
 * Task controller.
 *
 */
class TaskController extends Controller
{

    /**
     * Lists all Task entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmeDemoBundle:Task')->findAll();

        return $this->render('AcmeDemoBundle:Task:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    public function newAction()
    {
        $entity     = new Task();
        // dummy code - this is here just so that the Task has some tags
        // otherwise, this isn't an interesting example
        $tag1       = new Tag();
        $tag1->name = 'tag1';
        $entity->getTags()->add($tag1);

        $tag2       = new Tag();
        $tag2->name = 'tag2';
        $entity->getTags()->add($tag2);


        $form = $this->createForm(new TaskType(), $entity);


        return $this->render('AcmeDemoBundle:Task:new.html.twig', array(
                    'entity' => $entity,
                    'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Task entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Task();


        // dummy code - this is here just so that the Task has some tags
        // otherwise, this isn't an interesting example
        $tag1       = new Tag();
        $tag1->name = 'tag1';
        $entity->getTags()->add($tag1);
        $tag2       = new Tag();
        $tag2->name = 'tag2';
        $entity->getTags()->add($tag2);
        // end dummy code

        $form = $this->createForm(new TaskType(), $entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('task_show', array('id' => $entity->getId())));
        }

        return $this->render('AcmeDemoBundle:Task:new.html.twig', array(
                    'entity' => $entity,
                    'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Task entity.
     *
     * @param Task $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('task_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Task entity.
     *
     */

    /**
     * Finds and displays a Task entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeDemoBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AcmeDemoBundle:Task:show.html.twig', array(
                    'entity'      => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeDemoBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm   = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AcmeDemoBundle:Task:edit.html.twig', array(
                    'entity'      => $entity,
                    'edit_form'   => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Task entity.
     *
     * @param Task $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('task_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Task entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeDemoBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('task_edit', array('id' => $id)));
        }

        return $this->render('AcmeDemoBundle:Task:edit.html.twig', array(
                    'entity'      => $entity,
                    'edit_form'   => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Task entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcmeDemoBundle:Task')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Task entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('task'));
    }

    /**
     * Creates a form to delete a Task entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('task_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}