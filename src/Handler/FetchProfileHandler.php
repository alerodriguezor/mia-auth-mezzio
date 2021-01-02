<?php

namespace Mia\Auth\Handler;

/**
 * Description of FetchProfileHandler
 *
 * @author matiascamiletti
 */
class FetchProfileHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener usuario
        $user = $this->getUser($request);
        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse($user->toArray());
    }
}