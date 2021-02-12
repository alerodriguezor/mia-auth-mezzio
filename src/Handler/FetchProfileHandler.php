<?php

namespace Mia\Auth\Handler;

/**
 * Description of FetchProfileHandler
 * 
 * @OA\Post(
 *     path="/mia-user/me",
 *     summary="Get data of current user",
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/MIAUser")
 *     )
 * )
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