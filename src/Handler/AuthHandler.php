<?php

namespace Mia\Auth\Handler;

use Mia\Auth\Helper\JwtHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Mia\Core\Diactoros\MiaJsonErrorResponse;

/**
 * Description of AuthInternalHandler
 *
 * @author matiascamiletti
 */
class AuthHandler extends \Mia\Core\Middleware\MiaBaseMiddleware
{
    use JwtHelper;

    public function __construct($config)
    {
        // Setear configuración inicial
        $this->setConfig($config);
    }
    /**
     * 
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // Verifiy Method
        if($this->method == 'jwt'){
            return $this->useJwt($request, $handler);
        }

        return $this->useApiKey($request, $handler);
    }

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
            return new MiaJsonErrorResponse(-2, 'Authorization failed');
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
            $payload = $this->decodeToken($request->getHeaderLine('Bearer'));
            // Obtener usuario
            $user = \Mia\Auth\Repository\MIAUserRepository::findByID($payload->uid);
            // Obtener Usuario para guardarlo
            return $handler->handle($request->withAttribute(\Mia\Auth\Model\MIAUser::class, $user));
        } catch (\Exception $th) {
            return new MiaJsonErrorResponse(-2, 'Authorization failed');
        }
    }

    /**
     * Devuelve el accessToken enviado
     * @return string
     */
    protected function getAccessToken(ServerRequestInterface $request)
    {
        return $this->getParam($request, 'access_token');
    }
}
