<?php

namespace Somtel\RemitOneBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RemitOneBundle:Default:index.html.twig');
    }
}
