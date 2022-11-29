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

        return $this->useJwtWithResponse($account);
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
}