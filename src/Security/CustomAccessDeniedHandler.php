<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Twig\Environment as TwigEnvironment;

class CustomAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $twig;

    public function __construct(TwigEnvironment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        $content = $this->twig->render('bundles/TwigBundle/Exception/error.html.twig', [
            'status_code' => Response::HTTP_FORBIDDEN,
            'status_text' => 'Forbidden',
        ]);

        return new Response($content, Response::HTTP_FORBIDDEN);
    }
}
