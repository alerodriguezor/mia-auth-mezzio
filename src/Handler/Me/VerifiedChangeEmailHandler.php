<?php

namespace Mia\Auth\Handler\Me;

use Mia\Auth\Model\MiaUserNewEmail;

/**
 * Description of MiaPasswordRecoveryHandler
 *
 * @author matiascamiletti
 */
class VerifiedChangeEmailHandler extends \Mia\Core\Request\MiaRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Get params
        $email = $this->getParam($request, 'email', '');
        $token = $this->getParam($request, 'token', '');
        // Verificar si ya existe la cuenta
        $row = MiaUserNewEmail::where('email', $email)->where('token', $token)->first();
        if($row === null||$row->status == MiaUserNewEmail::STATUS_USED){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-1, 'Invalid token');
        }
        // Get user and update 
        $user = $row->user;
        $user->email = $row->email;
        $user->save();
        // Update row
        $row->status = MiaUserNewEmail::STATUS_USED;
        $row->save();
        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}

