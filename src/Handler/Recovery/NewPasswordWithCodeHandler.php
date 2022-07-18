<?php

namespace Mia\Auth\Handler\Recovery;

use Mia\Core\Helper\MiaErrorHelper;

/**
 * Description of MiaRecoveryHanlder
 * 
 *
 * @author matiascamiletti
 */
class NewPasswordWithCodeHandler extends \Mia\Core\Request\MiaRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros obligatorios
        $email = $this->getParam($request, 'email', '');
        $password = $this->getParam($request, 'password', '');
        $code = $this->getParam($request, 'code', '');
        // Verificar si ya existe la cuenta
        $account = \Mia\Auth\Model\MIAUser::where('email', $email)->first();
        if($account === null){
            return MiaErrorHelper::toLangEs($request, -1, 'Este email no existe', 'This email is not exist.');
        }
        // Buscar si existe el token
        $recovery = \Mia\Auth\Model\MiaRecoveryCode::where('user_id', $account->id)->where('code', $code)->where('status', \Mia\Auth\Model\MiaRecoveryCode::STATUS_PENDING)->first();
        if($recovery === null){
            return MiaErrorHelper::toLangEs($request, -1, 'El codigo es incorrecto', 'This code is incorrect.');
        }
        $recovery->status = \Mia\Auth\Model\MiaRecoveryCode::STATUS_USED;
        $recovery->save();
        // Guardar nueva contraseña
        $account->password = \Mia\Auth\Model\MIAUser::encryptPassword($password);
        $account->save();
        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}
