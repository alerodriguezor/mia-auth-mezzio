<?php

namespace Mia\Auth\Handler\Social;

use Mia\Auth\Handler\RegisterHandler;
use Mia\Auth\Helper\FirebaseHelper;
use Mia\Auth\Helper\JwtHelper;
use Mia\Auth\Model\MIAUser;
use Mia\Core\Diactoros\MiaJsonErrorResponse;
use Mia\Core\Helper\StringHelper;

/**
 * Description of GoogleSignInHandler
 * 
 * @OA\Post(
 *     path="/mia-auth/login-with-google",
 *     summary="Get all data",
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/MIAUser")
 *     )
 * )
 *
 * @author matiascamiletti
 */
class FirebaseSignInHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    use JwtHelper;

    /**
     *
     * @var array
     */
    protected $params = null;
    /**
     *
     * @var FirebaseHelper
     */
    protected $service;

    protected $providerId = 0;
    
    public function __construct($params, $configAuth)
    {
        $this->setConfig($configAuth);
        $this->params = $params;
        $this->service = new FirebaseHelper($this->params['keyFilePath']);
    }
    
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $user = $this->service->verifyIdToken($this->getParam($request, 'token', ''));
        if($user === null){
            return new MiaJsonErrorResponse(-3, 'Token is incorrect');
        }
        // Buscamos si este email tiene cuenta de Google
        $email = $user->email;
        if($email == null){
            $email = $user->providerData[0]->email;
        }
        if($email == null){
            $email = $user->uid . '@social.app';
        }

        $account = MIAUser::where('email', $email)->first();
        if($account === null){
            $account = $this->createAccount($user);
        }

        // Verify method
        if($this->method == 'jwt'){
            return $this->useJwtWithResponse($account);
        }

        return $this->useApiKey($request, $account);
    }
    /**
     * 
     */
    protected function createAccount(\Kreait\Firebase\Auth\UserRecord $user)
    {
        $nameData = StringHelper::splitName($user->displayName);
        // Creamos cuenta
        $account = new \Mia\Auth\Model\MIAUser();
        $account->firstname = $nameData[0];
        $account->lastname = $nameData[1];

        $email = $user->email;
        if($email == null){
            $email = $user->providerData[0]->email;
        }
        if($email == null){
            $email = $user->uid . '@social.app';
        }
        $account->email = $email;

        $account->phone = $user->phoneNumber;
        $account->photo = $user->photoUrl;
        $account->password = 'password_not_assigned';
        $account->role = \Mia\Auth\Model\MIAUser::ROLE_GENERAL;
        $account->status = \Mia\Auth\Model\MIAUser::STATUS_ACTIVE;
        $account->save();

        return $account;
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
        return new \Mia\Core\Diactoros\MiaJsonResponse(array('access_token' => $token->toArray(), 'user' => $account->toArray())
        );
    }
}