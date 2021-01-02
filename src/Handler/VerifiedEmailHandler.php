<?php


namespace Mia\Auth\Handler;

/**
 * Description of VerifiedEmailHandler
 *
 * @author matiascamiletti
 */
class VerifiedEmailHandler extends \Mia\Core\Request\MiaRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros obligatorios
        $email = $this->getParam($request, 'email', '');
        $token = $this->getParam($request, 'token', '');
        // Verificar si ya existe la cuenta
        $account = \Mia\Auth\Model\MIAUser::where('email', $email)->first();
        if($account === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-1, 'No existe este mail');
        }
        // Buscar si existe el token
        $recovery = \Mia\Auth\Model\MIAActive::where('user_id', $account->id)->where('token', $token)->first();
        if($recovery === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(-1, 'El token es incorrecto');
        }
        $recovery->status = \Mia\Auth\Model\MIAActive::STATUS_USED;
        $recovery->save();
        // Guardar nuevo estado
        $account->status = 1;
        $account->save();
        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}

