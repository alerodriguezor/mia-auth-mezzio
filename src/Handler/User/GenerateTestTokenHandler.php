<?php

namespace Mia\Auth\Handler\User;

use Mia\Auth\Helper\JwtHelper;
use Mia\Auth\Model\MIAUser;

/**
 * Description of FetchHandler
 *
 * @author matiascamiletti
 */
class GenerateTestTokenHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    use JwtHelper;
    
    /**
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtenemos ID si fue enviado
        $email = $this->getParam($request, 'email', '');
        // Buscar si existe el tour en la DB
        $account = \Mia\Auth\Model\MIAUser::where('email', $email)->first();
        // verificar si existe
        if($account === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(1, 'The element is not exist.');
        }
        $accessToken = '';
        try {
            $accessToken = $this->generateToken($account->id, $account->email);
        } catch (\Exception $th) {
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-3, 'Problem with generate token');
        }

        $data = $account->toArray();
        $data['token_type'] = 'bearer';
        $data['access_token'] = $accessToken;

        return new \Mia\Core\Diactoros\MiaJsonResponse($data);
    }
}