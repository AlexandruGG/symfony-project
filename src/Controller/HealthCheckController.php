<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class HealthCheckController
{
    /**
     * @Route("/health")
     */
    public function HealthCheck()
    {
        return new JsonResponse(['status' => 'OK']);
    }
}