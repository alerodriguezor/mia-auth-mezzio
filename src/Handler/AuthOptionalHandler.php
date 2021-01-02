<?php

namespace Mia\Auth\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Mia\Core\Diactoros\MiaJsonErrorResponse;

/**
 * Description of AuthOptionalHandler
 *
 * @author matiascamiletti
 */
class AuthOptionalHandler extends AuthHandler
{
    /**
     * 
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // obtener accessToken
        $accessToken = $this->getAccessToken($request);
        // Buscamos el Token en la DB
        $row = \Mia\Auth\Model\MIAAccessToken::where('access_token', $accessToken)->first();
        // Validar AccessToken
        if($row === null){
            return $handler->handle($request->withAttribute(\Mia\Auth\Model\MIAUser::class, null));
        }
        // Obtener usuario
        $user = \Mia\Auth\Repository\MIAUserRepository::findByID($row->user_id);
        // Obtener Usuario para guardarlo
        return $handler->handle($request->withAttribute(\Mia\Auth\Model\MIAUser::class, $user));
    }
}
