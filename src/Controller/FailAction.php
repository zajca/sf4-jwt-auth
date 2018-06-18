<?php declare(strict_types=1);

namespace App\Controller;

use App\SerializerResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class FailAction
{
    
    /**
     * @Route("/no-api/fail", methods={"GET"})
     */
    public function __invoke(): SerializerResponse
    {
        throw new NotFoundHttpException();
    }
}
