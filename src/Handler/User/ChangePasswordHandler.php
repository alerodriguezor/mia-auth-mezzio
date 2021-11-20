<?php

namespace Mia\Auth\Handler\User;

use Mia\Auth\Model\MIAUser;

/**
 * Description of FetchHandler
 *
 * @author matiascamiletti
 */
class ChangePasswordHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    /**
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtenemos ID si fue enviado
        $itemId = $this->getParam($request, 'id', '');
        // Buscar si existe el tour en la DB
        $item = MIAUser::find($itemId);
        // verificar si existe
        if($item === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(1, 'The element is not exist.');
        }
        // Get password
        $password = $this->getParam($request, 'password', '');
        // Save new password
        $item->password = \Mia\Auth\Model\MIAUser::encryptPassword($password);
        // Save in DB
        $item->save();
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}