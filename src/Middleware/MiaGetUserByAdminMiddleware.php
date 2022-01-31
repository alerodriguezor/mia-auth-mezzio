<?php

namespace Mia\Auth\Middleware;

use Mia\Auth\Model\MIAUser;

/**
 * Description of MiaAuthMiddleware
 *
 * @author matiascamiletti
 */
class MiaGetUserByAdminMiddleware extends MiaAuthMiddleware
{    
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler) : \Psr\Http\Message\ResponseInterface
    {
        // Obtener usuario
        $user = $this->getUser($request);
        // Verificar si es administrador
        if($user->role != MIAUser::ROLE_ADMIN){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-100, 'Your has not permission.');
        }
        // Get Param
        $userId = $this->getParam($request, 'user_id', null) ? $this->getParam($request, 'user_id', null) : $this->getParam($request, 'id', null);
        // Get User 
        $editUser = \Mia\Auth\Repository\MIAUserRepository::findByID($userId);
        if($editUser == null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-100, 'Param or user not exist.');
        }
        // Obtener Usuario para guardarlo
        return $handler->handle($request->withAttribute(\Mia\Auth\Model\MIAUser::class, $editUser));
    }
}