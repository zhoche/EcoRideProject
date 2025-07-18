<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthController
{
    #[Route('/healthz', name: 'healthz')]
    public function health(): Response
    {
        return new Response('OK', 200);
    }
}