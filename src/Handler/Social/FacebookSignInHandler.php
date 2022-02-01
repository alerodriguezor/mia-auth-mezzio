<?php

namespace Mia\Auth\Handler\Social;

use Mia\Auth\Model\MIAProvider;

/**
 * Description of GoogleSignInHandler
 * 
 * @OA\Post(
 *     path="/mia-auth/login-with-facebook",
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
class FacebookSignInHandler extends FirebaseSignInHandler
{
    protected $providerId = MIAProvider::PROVIDER_FACEBOOK;
}