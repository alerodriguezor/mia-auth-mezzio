<?php


namespace Mia\Auth\Handler;

/**
 * Description of VerifiedEmailHandler
 *
 * @author matiascamiletti
 */
class VerifyExistPhoneHandler extends \Mia\Core\Request\MiaRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros obligatorios
        $phone = $this->getParam($request, 'phone', '');
        // Verificar si ya existe la cuenta
        $account = \Mia\Auth\Model\MIAUser::where('phone', $phone)->first();
        if($account === null){
            return new \Mia\Core\Diactoros\MiaJsonResponse(false);
        }
        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}

