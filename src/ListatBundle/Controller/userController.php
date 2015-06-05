<?php

namespace ListatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function userAction()
    {
        return $this->render('ListatBundle:Default:index.html.twig');
    }

}