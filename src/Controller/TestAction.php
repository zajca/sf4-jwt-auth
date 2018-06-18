<?php declare(strict_types=1);

namespace App\Controller;

use App\SerializerResponse;
use Symfony\Component\Routing\Annotation\Route;

final class TestAction
{
    
    use UserControllerTrait;
    
    /**
     * @Route("/api/test", methods={"GET"})
     */
    public function __invoke(): SerializerResponse
    {
        return new SerializerResponse($this->getUser());
    }
}
