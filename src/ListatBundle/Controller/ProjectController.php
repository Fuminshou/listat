<?php

namespace ListatBundle\Controller;

use ListatBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ListatBundle\Form\Type\ProjectType;

class ProjectController extends Controller
{
    public function projectListAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new ProjectType());

        $user = $em->getRepository('ListatBundle\\Entity\\User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'There is no user with id '.$id
            );
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $project->setUser($user);

            $em->persist($project);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'The new project has been successfully created!'
            );

            return $this->redirect($this->generateUrl('listat_project_list', array('id' => $id)));
        }

        $projects = $em->getRepository('ListatBundle\\Entity\\Project')->findBy(array(
            'user' => $user
        ));

        return $this->render('ListatBundle:Default:project.html.twig',
            array(
                'user' => $user,
                'projects' => $projects,
                'form' => $form->createView()
            )
        );
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ListatBundle\\Entity\\Project')->find($id);

        if (!$project) {
            throw $this->createNotFoundException(
                'There is no project with id '.$id
            );
        }

        $em->remove($project);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'notice',
            'The project has been deleted!'
        );

        $user = $project->getUser();

        return $this->redirect($this->generateUrl('listat_project_list', array('id' => $user->getId())));
    }

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ListatBundle\\Entity\\Project')->find($id);

        if (!$project) {
            throw $this->createNotFoundException(
                'There is no project with id '.$id
            );
        }

        $form = $this->createForm(new ProjectType(), $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();


            $em->persist($project);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes have been saved!'
            );

            $user = $project->getUser();

            return $this->redirect($this->generateUrl('listat_project_list', array('id' => $user->getId())));
        }

        return $this->render('ListatBundle:Default:edit_project.html.twig',
            array(
                'project' => $project,
                'form' => $form->createView()
            )
        );
    }

    public function lastUpdateAction(Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $lastUpdate = $em->getRepository('ListatBundle\\Entity\\Project')->findLastUpdateByProject($project);

        return $this->render('ListatBundle::parts/last_update.html.twig',
            array(
                'lastUpdate' => $lastUpdate,
            )
        );
    }
}
