<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ShowController extends AbstractController
{
    /**
     * @Route("/show", name="show")
     */
    public function index()
    {
        return $this->render('show/index.html.twig', [
            'controller_name' => 'ShowController',
        ]);
    }

   
    public function list($slug)
    {
        //return new Response('Welcome Mr : '.$slug);
        //return new Response(sprintf('welcome Mr : %s',$slug));
        return new Response('<html><head></head><body>' . rand(1, 9) . '</body></html>');

    }
}
