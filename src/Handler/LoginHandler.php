<?php

namespace Mia\Auth\Handler;

use Mia\Auth\Helper\JwtHelper;
use Mia\Auth\Model\MIAUser;
use Mia\Core\Helper\MiaErrorHelper;

/**
 * Description of LoginHandler
 * 
 * @OA\Post(
 *     path="/mia-auth/login",
 *     summary="Login User",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         description="Login user",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",                 
 *             @OA\Schema(
 *                  @OA\Property(
 *                      property="email",
 *                      type="string",
 *                      description="Email of user",
 *                      example="matias@agencycoda.com"
 *                  ),
 *                  @OA\Property(
 *                      property="password",
 *                      type="string",
 *                      description="Password of user",
 *                      example="123Qwerty"
 *                  )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/MIAUser")
 *     )
 * )
 *
 * @author matiascamiletti
 */
class LoginHandler extends \Mia\Core\Request\MiaRequestHandler
{
    use JwtHelper;

    public function __construct($config)
    {
        // Setear configuración inicial
        $this->setConfig($config);
    }

    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros obligatorios
        $email = $this->getParam($request, 'email', '');
        $password = $this->getParam($request, 'password', '');
        // Verificar si ya existe la cuenta
        $account = \Mia\Auth\Model\MIAUser::where('email', $email)->first();
        if($account === null){
            return MiaErrorHelper::toLangEs($request, -3, 'Esta cuenta no existe', 'This account does not exist');
        }
        // Verificar si la contraseña coincide
        if(!\Mia\Auth\Model\MIAUser::verifyPassword($password, $account->password)){
            return MiaErrorHelper::toLangEs($request, -3, 'La contraseña es incorrecta', 'Password is not correct');
        }
        // Valid if user is active
        if($this->validStatus && $account->status == MIAUser::STATUS_PENDING){
            return MiaErrorHelper::toLangEs($request, -4, 'Tu cuenta no esta activa', 'Your account is not active.');
        }else if($this->validStatus && $account->status == MIAUser::STATUS_BLOCKED){
            return MiaErrorHelper::toLangEs($request, -5, 'Tu cuenta esta bloqueada', 'Your account is blocked.');
        }
        // Verify method
        if($this->method == 'jwt'){
            return $this->useJwtWithResponse($account);
        }else if ($this->method == 'api-key-v2') {
            return $this->useApiKeyV2($request, $account);
        }

        return $this->useApiKey($request, $account);
    }

    protected function useApiKeyV2(\Psr\Http\Message\ServerRequestInterface $request, \Mia\Auth\Model\MIAUser $account): \Psr\Http\Message\ResponseInterface
    {
        // Generar nuevo AccessToken
        $token = new \Mia\Auth\Model\MIAAccessToken();
        $token->user_id = $account->id;
        $token->access_token = \Mia\Auth\Model\MIAAccessToken::generateAccessToken();
        $token->expires = \Mia\Auth\Model\MIAAccessToken::generateExpires();
        $token->platform = $this->getParam($request, 'platform', \Mia\Auth\Model\MIAAccessToken::PLATFORM_WEB);
        $token->version = $this->getParam($request, 'version', '');
        $token->device_data = $this->getParam($request, 'device_data', '');
        $token->save();

        $data = $account->toArray();
        $data['token_type'] = 'none';
        $data['access_token'] = $token->access_token;

        return new \Mia\Core\Diactoros\MiaJsonResponse($data);
    }

    protected function useApiKey(\Psr\Http\Message\ServerRequestInterface $request, \Mia\Auth\Model\MIAUser $account): \Psr\Http\Message\ResponseInterface
    {
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