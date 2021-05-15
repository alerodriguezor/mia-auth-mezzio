<?php

namespace Mia\Auth\Middleware;

use Mia\Auth\Model\MIAPermission;

/**
 * Description of MiaAuthMiddleware
 *
 * @author matiascamiletti
 */
class MiaPermissionMiddleware extends MiaAuthMiddleware
{
    /**
     * 
     * @var string
     */
    protected $permissionVerify = '';
    /**
     * 
     * @param string
     */
    public function __construct($permissionName = '')
    {
        $this->permissionVerify = $permissionName;
    }
    
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler) : \Psr\Http\Message\ResponseInterface
    {
        // Obtener usuario
        $user = $this->getUser($request);
        // Verificar si tiene permisos para esta acción
        $permission = MIAPermission::
                        select('mia_permission.*')
                        ->select('mia_role_access.type')
                        ->join('mia_role_access', 'mia_role_access.permission_id', 'mia_permission.id')
                        ->where('mia_role_access.role_id', $user->role)
                        ->where('mia_permission.title', $this->permissionVerify)
                        ->first();
        if($permission === null||$permission->type == 1){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-100, 'Your has not permission.');
        }
        // Devolver repuesta
        return $handler->handle($request);
    }
}