<?php declare(strict_types=1);

namespace App\Controller;

use App\Base\Rest\View;
use App\Base\UserControllerTrait;
use Symfony\Component\Routing\Annotation\Route;

final class CheckTokenAction
{
    
    use UserControllerTrait;
    
    /**
     * @Route("/api/token_check", methods={"GET"})
     */
    public function __invoke(): View
    {
        $this->getUser();
        
        return new View('OK');
    }
}
