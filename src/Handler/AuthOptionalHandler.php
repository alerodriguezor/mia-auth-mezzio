<?php

namespace Mia\Auth\Handler;

use Mia\Auth\Helper\JwtHelper;
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
    protected function useApiKey(ServerRequestInterface $request, RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        // obtener accessToken
        $accessToken = $this->getAccessToken($request);
        if(isset($_SERVER['HTTP_X_API_KEY'])){
            $accessToken = $_SERVER['HTTP_X_API_KEY'];
        }
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

    protected function useJwt(ServerRequestInterface $request, RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        try {
            // Process Token
            $payload = $this->decodeToken(str_replace('Bearer ', '', $request->getHeaderLine('Authorization')));
            // Obtener usuario
            $user = \Mia\Auth\Repository\MIAUserRepository::findByID($payload->uid);
        } catch (\Exception $th) {
            return $handler->handle($request->withAttribute(\Mia\Auth\Model\MIAUser::class, null));
        }
        // Obtener Usuario para guardarlo
        return $handler->handle($request->withAttribute(\Mia\Auth\Model\MIAUser::class, $user));
    }
}
