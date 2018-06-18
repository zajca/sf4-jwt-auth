<?php declare(strict_types=1);

namespace App\Listener;


use App\SerializerResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerListener implements EventSubscriberInterface
{
    
    /**
     * @var SerializerInterface
     */
    private $serializer;
    
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 30],
        ];
    }
    
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        if ($result instanceof SerializerResponse) {
            $json = $this->serializer->serialize($result->getData(), $result->getFormat(), $result->getContext());
            $response = new JsonResponse(null, $result->getStatusCode(), $result->getHeaders());
            $response->setJson($json);
            $event->setControllerResult($response);
            $event->setResponse($response);
        }
    }
}
