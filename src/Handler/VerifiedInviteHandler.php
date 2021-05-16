<?php


namespace Mia\Auth\Handler;

use Mia\Auth\Model\MIAUser;

/**
 * Description of VerifiedEmailHandler
 *
 * @author matiascamiletti
 */
class VerifiedInviteHandler extends \Mia\Core\Request\MiaRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros obligatorios
        $email = $this->getParam($request, 'email', '');
        $token = $this->getParam($request, 'token', '');
        $password = $this->getParam($request, 'password', '');
        // Verificar si ya existe la cuenta
        $account = \Mia\Auth\Model\MIAUser::where('email', $email)->first();
        if($account === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-1, 'The email is incorrect');
        }
        // Buscar si existe el token
        $recovery = \Mia\Auth\Model\MIAInvite::where('user_id', $account->id)->where('token', $token)->first();
        if($recovery === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-1, 'The token is incorrect');
        }
        if($recovery->status != \Mia\Auth\Model\MIAInvite::STATUS_PENDING){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-1, 'The token is incorrect');
        }
        $recovery->status = \Mia\Auth\Model\MIAInvite::STATUS_USED;
        $recovery->save();
        // Guardar nuevo estado
        $account->password = \Mia\Auth\Model\MIAUser::encryptPassword($password);
        $account->status = MIAUser::STATUS_ACTIVE;
        $account->save();
        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}

