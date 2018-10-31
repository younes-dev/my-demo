<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * ("/", name="index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * ("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('Admin/index.html.twig');
    }

    public function catalog($slug)
    {
        //return new Response('Welcome Mr : '.$slug);
        return new Response(sprintf('welcome Mr : %s',$slug));

    }

}
