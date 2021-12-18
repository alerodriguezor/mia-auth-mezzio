<?php

namespace Mia\Auth\Handler\Me;

use Mia\Auth\Model\MiaUserNewEmail;

/**
 * Description of ChangePasswordHandler
 *
 * @author matiascamiletti
 */
class ChangeEmailHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros
        $newEmail = $this->getParam($request, 'email', '');
        // Verify if email exist
        $account = \Mia\Auth\Model\MIAUser::where('email', $newEmail)->first();
        if($account !== null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-1, 'This email exist');
        }
        // Obtener usuario
        $user = $this->getUser($request);
        // Generate new token
        $token = \Mia\Auth\Model\MIAUser::encryptPassword($newEmail . '_' . time() . '_' . $newEmail);
        // Add in DB
        $row = new MiaUserNewEmail();
        $row->user_id = $user->id;
        $row->status = MiaUserNewEmail::STATUS_PENDING;
        $row->token = $token;
        $row->email = $newEmail;
        $row->save();
        
        /* @var $sendgrid \Mia\Mail\Service\Sendgrid */
        $sendgrid = $request->getAttribute('Sendgrid');
        $lang = $this->getParam($request, 'lang', 'en');
        $result = $sendgrid->send($newEmail, 'change-email-' . $lang, [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email_old' => $user->email,
            'email' => $newEmail,
            'token' => $token
        ]);

        if($result === false){
            return new \Mia\Core\Diactoros\MiaJsonResponse(false);
        }

        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}
