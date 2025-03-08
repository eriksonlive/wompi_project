<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RoutesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function fetchData(Request $request)
    {
        return $this->render('index.html.twig');
    }
}
