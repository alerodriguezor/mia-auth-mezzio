<?php

namespace Mia\Auth\Handler\Role;

use Mia\Auth\Model\MIARole;

/**
 * Description of ListHandler
 * 
 * @OA\Get(
 *     path="/mia-auth/role/all",
 *     summary="Get all roles",
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/MIARole")
 *     )
 * )
 *
 * @author matiascamiletti
 */
class AllHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtenemos información
        $rows = MIARole::orderBy('title', 'asc')->get();
        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse($rows->toArray());
    }
}