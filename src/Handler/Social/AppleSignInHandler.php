<?php

namespace Mia\Auth\Handler\Social;

use Mia\Auth\Model\MIAProvider;

/**
 * Description of AppleSignInHandler
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
class AppleSignInHandler extends FirebaseSignInHandler
{
    protected $providerId = MIAProvider::PROVIDER_APPLE;
}