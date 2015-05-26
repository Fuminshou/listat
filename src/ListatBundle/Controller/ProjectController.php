<?php

namespace ListatBundle\Controller;

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
}
