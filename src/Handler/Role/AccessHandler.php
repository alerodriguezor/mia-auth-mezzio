<?php

namespace Mia\Auth\Handler\Role;

use Mia\Auth\Model\MIAPermission;
use Mia\Auth\Model\MIARole;

/**
 * Description of ListHandler
 * 
 * @OA\Get(
 *     path="/mia-auth/role/access",
 *     summary="Get all permission for current user",
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/MIARole")
 *     )
 * )
 *
 * @author matiascamiletti
 */
class AccessHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Get Current user
        $user = $this->getUser($request);
        // Get all permissions Allow for user
        $rows = MIAPermission::select('mia_permission.*')->join('mia_role_access', 'mia_role_access.permission_id', 'mia_permission.id')->where('mia_role_access.role_id', $user->role)->get();
        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse($rows->toArray());
    }
}