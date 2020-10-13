<?php


namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /**
     * Handles an access denied failure.
     *
     * @return Response|null
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
    $redirect = $request->headers->get('referer');
        $content = "<error-page>
                    <error-code>403</error-code>
                    <location> / Accès refusé <a href='$redirect'>Retour</a> </location>
                        </error-page>";

        return new Response($content, 403);
    }
}