<?php declare(strict_types=1);

namespace App\Controller;

use App\Form\TestDTO;
use App\Form\TestForm;
use App\SerializerResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FormAction
{
    
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    
    public function __construct(
        FormFactoryInterface $formFactory
    ) {
        $this->formFactory = $formFactory;
    }
    
    /**
     * @Route("/no-api/form", methods={"POST"})
     */
    public function __invoke(Request $request): SerializerResponse
    {
        $form = $this->formFactory->create(
            TestForm::class,
            new TestDTO,
            ['method' => 'POST']
        );
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TestDTO $dto */
            $dto = $form->getData();
            
            return new SerializerResponse($dto);
        }
        
        return new SerializerResponse($form, Response::HTTP_BAD_REQUEST);
    }
}
