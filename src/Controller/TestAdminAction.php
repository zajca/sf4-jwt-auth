<?php declare(strict_types=1);

namespace App\Controller;

use App\SerializerResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

final class TestAdminAction
{
    
    use UserControllerTrait;
    
    /**
     * @Route("/api/test-admin", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function __invoke(): SerializerResponse
    {
        return new SerializerResponse($this->getUser());
    }
}
