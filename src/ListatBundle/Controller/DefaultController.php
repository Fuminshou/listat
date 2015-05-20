<?php

namespace ListatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ListatBundle\Entity\Task;


class DefaultController extends Controller
{
    public function indexAction()
    {
        $project = array(
            'Progetto uno',
            'Secondo progetto',
            'Ultimo progetto',
            'Progetto nuovo'
        );

        return $this->render('ListatBundle:Default:index.html.twig', array('project' => $project));
    }


    public function tasklistAction(Request $request, $name)
    {
        $task = new Task();

        $form = $this->createFormBuilder($task)
            ->add('task', 'text')
            ->add('dueDate', 'date')
            ->add('save', 'submit', array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // perform some action, such as saving the task to the database

            return $this->render('ListatBundle:Default:tasklist.html.twig',
                array('name' => $name,
                      'form' => $form->createView()
                )
            );
        }

        //creazione task fallita
        return $this->render('ListatBundle:Default:tasklist.html.twig',
            array('name' => $name,
                  'form' => $form->createView()
            )
        );
    }
}
