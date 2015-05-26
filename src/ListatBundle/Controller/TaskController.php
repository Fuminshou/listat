<?php

namespace ListatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ListatBundle\Form\Type\TaskType;

class TaskController extends Controller
{
    public function tasklistAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new TaskType());

        $project = $em->getRepository('ListatBundle\\Entity\\Project')->find($id);    //mettere try-catch per eccezione not found

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setProject($project);

            $em->persist($task);
            $em->flush();

            return $this->redirect($this->generateUrl('listat_tasklist', array('id' => $id)));
        }

        $tasks = $em->getRepository('ListatBundle\\Entity\\Task')->findBy(array(
            'project' => $project
        ));

        return $this->render('ListatBundle:Default:tasklist.html.twig',
            array(
                'project' => $project,
                'form' => $form->createView(),
                'tasks' => $tasks
            )
        );
    }
}
