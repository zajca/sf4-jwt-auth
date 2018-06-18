<?php declare(strict_types=1);

namespace App\Controller;


use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ExceptionController
{
    
    protected $debug;
    
    /**
     * @var SerializerInterface
     */
    private $serializer;
    
    /**
     * @var TranslatorInterface
     */
    private $translator;
    
    public function __construct(bool $debug, SerializerInterface $serializer, TranslatorInterface $translator)
    {
        $this->debug = $debug;
        $this->serializer = $serializer;
        $this->translator = $translator;
    }
    
    /**
     * @param int $startObLevel
     *
     * @return string
     */
    protected function getAndCleanOutputBuffering($startObLevel)
    {
        if (ob_get_level() <= $startObLevel) {
            return '';
        }
        
        Response::closeOutputBuffers($startObLevel + 1, true);
        
        return ob_get_clean();
    }
    
    public function __invoke(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));
        
        $message = $exception->getMessage();
        if ($message === null || $message === '') {
            $message = array_key_exists($exception->getStatusCode(), Response::$statusTexts) ? Response::$statusTexts[$exception->getStatusCode()] : 'error';
        }
        $data = [
            'code'    => $exception->getStatusCode(),
            'message' => $this->translator->trans($message, [], 'error'),
        ];
        if ($this->debug === true) {
            $data = \array_merge(
                $data, [
                         'exception'      => $exception,
                         'currentContent' => $currentContent,
                         'logger'         => $logger,
                     ]
            );
        }
        
        $json = $this->serializer->serialize($data, 'json');
        $response = new JsonResponse(null, $exception->getStatusCode(), $exception->getHeaders());
        $response->setJson($json);
        
        return $response;
        
    }
    
}
