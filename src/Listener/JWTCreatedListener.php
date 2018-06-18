<?php declare(strict_types=1);

namespace App\Listener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\User\User;

class JWTCreatedListener
{
    
    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();
        /** @var User $user */
        $user = $event->getUser();
        $payload['id'] = $user->getUsername();
        $payload['fullName'] = $user->getUsername();
        
        $event->setData($payload);
    }
}
