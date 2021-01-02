<?php

namespace Mia\Auth\Handler;

/**
 * Description of LoginInternalHandler
 *
 * @author matiascamiletti
 */
class LoginHandler extends \Mia\Core\Request\MiaRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros obligatorios
        $email = $this->getParam($request, 'email', '');
        $password = $this->getParam($request, 'password', '');
        // Verificar si ya existe la cuenta
        $account = \Mia\Auth\Model\MIAUser::where('email', $email)->first();
        if($account === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-2, 'This account does not exist');
        }
        // Verificar si la contraseña coincide
        if(!\Mia\Auth\Model\MIAUser::verifyPassword($password, $account->password)){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-3, 'Password is not correct');
        }
        // Generar nuevo AccessToken
        $token = new \Mia\Auth\Model\MIAAccessToken();
        $token->user_id = $account->id;
        $token->access_token = \Mia\Auth\Model\MIAAccessToken::generateAccessToken();
        $token->expires = \Mia\Auth\Model\MIAAccessToken::generateExpires();
        $token->platform = $this->getParam($request, 'platform', \Mia\Auth\Model\MIAAccessToken::PLATFORM_WEB);
        $token->version = $this->getParam($request, 'version', '');
        $token->device_data = $this->getParam($request, 'device_data', '');
        $token->save();
        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse(
                array('access_token' => $token->toArray(), 'user' => $account->toArray())
        );
    }
}