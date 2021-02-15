<?php

namespace Mia\Auth\Helper;

use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;

/**
 * Description of MiaAuthMiddleware
 *
 * @author matiascamiletti
 */
class FirebaseHelper
{
    protected $service = null;
    
    public function __construct($filePath)
    {
        $this->service = (new Factory)->withServiceAccount($filePath);
    }
    /**
     * 
     * @param type $idToken
     * @return \Kreait\Firebase\Auth\UserRecord
     */
    public function verifyIdToken($idToken) 
    {
        $auth = $this->service->createAuth();

        try {
            $verifiedIdToken = $auth->verifyIdToken($idToken);
            $uid = $verifiedIdToken->claims()->get('sub');
            return $auth->getUser($uid);
        } catch (\InvalidArgumentException $e) {
            //echo 'The token could not be parsed: '.$e->getMessage();
            return null;
        } catch (InvalidToken $e) {
            //echo 'The token is invalid: '.$e->getMessage();
            return null;
        }

        return null;
    }
}