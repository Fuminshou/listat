<?php

namespace ListatBundle\Controller;

use ListatBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ListatBundle\Form\Type\ProjectType;

class ProjectController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new ProjectType());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $em->persist($project);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'The new project has been successfully created!'
            );

            return $this->redirect($this->generateUrl('listat_homepage'));
        }

        $projects = $em->getRepository('ListatBundle\\Entity\\Project')->findAll();

        return $this->render('ListatBundle:Default:index.html.twig',
            array(
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

        return $this->redirect($this->generateUrl('listat_homepage'));
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

            return $this->redirect($this->generateUrl('listat_homepage'));
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
