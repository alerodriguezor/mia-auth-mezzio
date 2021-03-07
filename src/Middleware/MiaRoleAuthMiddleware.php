<?php

namespace Mia\Auth\Middleware;
/**
 * Description of MiaAuthMiddleware
 *
 * @author matiascamiletti
 */
class MiaAuthMiddleware extends MiaAuthMiddleware
{
    /**
     * ID de roles que se van a verificar.
     * @var array
     */
    protected $roleVerify = [];
    /**
     * Asignar un array de roles a verificar
     * @param array $role
     */
    public function __construct($role = [\Mia\Auth\Model\MIAUser::ROLE_ADMIN])
    {
        $this->roleVerify = $role;
    }
    
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler) : \Psr\Http\Message\ResponseInterface
    {
        // Obtener usuario
        $user = $this->getUser($request);
        // Verificar si es administrador
        if(!in_array($user->role, $this->roleVerify)){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-100, 'Your has not permission.');
        }
        // Devolver repuesta
        return $handler->handle($request);
    }
}