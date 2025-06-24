<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;

class CorsPreflightRequestListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ($request->getMethod() === 'OPTIONS') {
            $origin = $request->headers->get('Origin') ?? '*';

            $response = new Response();
            $response->setStatusCode(200);

            // 🔥 headers dynamiques pour répondre au navigateur
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');

            $event->setResponse($response);
        }
    }
}
