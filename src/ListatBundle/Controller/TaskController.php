<?php

namespace ListatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ListatBundle\Form\Type\TaskType;

class TaskController extends Controller
{
    public function taskListAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new TaskType());

        $project = $em->getRepository('ListatBundle\\Entity\\Project')->find($id);

        if (!$project) {
            throw $this->createNotFoundException(
                'There is no project with id '.$id
            );
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setProject($project);
            $task->setLastUpdate();

            $em->persist($task);
            $em->flush();

            return $this->redirect($this->generateUrl('listat_task_list', array('id' => $id)));
        }

        $tasks = $em->getRepository('ListatBundle\\Entity\\Task')->findBy(array(
            'project' => $project
        ));

        return $this->render('ListatBundle:Default:task.html.twig',
            array(
                'project' => $project,
                'form' => $form->createView(),
                'tasks' => $tasks
            )
        );
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('ListatBundle\\Entity\\Task')->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'There is no project with id '.$id
            );
        }

        $em->remove($task);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'notice',
            'The project has been deleted!'
        );

        $project = $task->getProject();

        return $this->redirect($this->generateUrl('listat_task_list', array('id' => $project->getId())));
    }

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('ListatBundle\\Entity\\Task')->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'There is no task with id '.$id
            );
        }

        $form = $this->createForm(new TaskType(), $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setLastUpdate();

            $em->persist($task);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes have been saved!'
            );

            $project = $task->getProject();

            return $this->redirect($this->generateUrl('listat_task_list', array('id' => $project->getId())));
        }

        return $this->render('ListatBundle:Default:edit_task.html.twig',
            array(
                'task' => $task,
                'form' => $form->createView()
            )
        );
    }
}
