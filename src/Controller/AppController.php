<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private string $method;
    private string $route;
    private $pages = [
        'login' => [
            'title' => "Login",
            'url' => '/login/'
        ],
        'register' => [
            'title' => "Register",
            'url' => '/register/'
        ],
    ];
    public function __invoke(Request $request)
    {
        $this->method = $request->getMethod();
        $this->route = explode(':8000', $request->getUri())[1];
        if ($this->method === 'GET' && $this->route === "/")
            return $this->index();
    }

    #[Route('/', name: "app_home", methods: ['GET'])]
    public function index(): Response
    {

        return $this->render('index.html.twig', [
            "pages" => $this->pages
        ]);
    }
}
