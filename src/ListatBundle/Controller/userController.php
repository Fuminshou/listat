<?php

namespace ListatBundle\Controller;

use ListatBundle\Form\Type\UserLoginType;
use ListatBundle\Form\Type\UserRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function indexAction()
    {
        return $this->render('ListatBundle:Default:index.html.twig');
    }

    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new UserRegistrationType());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('listat_project_list', array('id' => $user->getId())));
        }

        return $this->render('ListatBundle:Default:registration.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function loginAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new UserLoginType());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mail = $form['email']->getData();
            $user = $em->getRepository('ListatBundle\\Entity\\User')->findOneBy(array('email' => $mail));

            if (!$user) {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'This email is not valid'
                );

                return $this->render('ListatBundle:Default:login.html.twig', array(
                    'form' => $form->createView()
                ));
            }

            $pass = $form['password']->getData();

            if ($pass !== $user->getPassword()) {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'This password is incorrect!'
                );

                return $this->render('ListatBundle:Default:login.html.twig', array(
                    'form' => $form->createView()
                ));
            }

            return $this->redirect($this->generateUrl('listat_project_list', array('id' => $user->getId())));
        }

        return $this->render('ListatBundle:Default:login.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function logoutAction()
    {
        return $this->render('ListatBundle:Default:index.html.twig');
    }
}