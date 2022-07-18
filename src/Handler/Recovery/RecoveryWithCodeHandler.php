<?php

namespace Mia\Auth\Handler\Recovery;

use Mia\Core\Helper\MiaErrorHelper;

/**
 * Description of MiaRecoveryHanlder
 * 
 *
 * @author matiascamiletti
 */
class RecoveryWithCodeHandler extends \Mia\Core\Request\MiaRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros obligatorios
        $email = $this->getParam($request, 'email', '');
        // Verificar si ya existe la cuenta
        $account = \Mia\Auth\Model\MIAUser::where('email', $email)->first();
        if($account === null){
            return MiaErrorHelper::toLangEs($request, -1, 'Este email no existe', 'This email is not exist.');
        }
        if($account->deleted == 1){
            return MiaErrorHelper::toLangEs($request, -1, 'Esta cuenta no existe', 'This account not exist.');
        }
        // Generate code ramdon
        $code = random_int(100000, 999999);
        // Generate db row
        $recovery = new \Mia\Auth\Model\MiaRecoveryCode();
        $recovery->user_id = $account->id;
        $recovery->status = \Mia\Auth\Model\MiaRecoveryCode::STATUS_PENDING;
        $recovery->code = $code;
        $recovery->save();
        
        /* @var $sendgrid \Mia\Mail\Service\Sendgrid */
        $sendgrid = $request->getAttribute('Sendgrid');
        $result = $sendgrid->send($account->email, 'recovery-password-with-code', [
            'firstname' => $account->firstname,
            'email' => $account->email,
            'code' => $code
        ]);

        if($result === false){
            return MiaErrorHelper::toLangEs($request, -15, 'No se ha podido enviar el email', 'The email is not sended.');
        }

        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}
