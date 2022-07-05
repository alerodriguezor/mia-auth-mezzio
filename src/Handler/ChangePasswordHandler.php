<?php

namespace Mia\Auth\Handler;

use Mia\Core\Helper\MiaErrorHelper;

/**
 * Description of ChangePasswordHandler
 *
 * @author matiascamiletti
 */
class ChangePasswordHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros
        $old = $this->getParam($request, 'old_password', '');
        $password = $this->getParam($request, 'password', '');
        // Obtener usuario
        $item = $this->getUser($request);
        // Verify if password is correct
        if($item->password != 'password_not_assigned' && !\Mia\Auth\Model\MIAUser::verifyPassword($old, $item->password)){
            return MiaErrorHelper::toLangEs($request, -3, 'La contraseña es incorrecta', 'Password is not correct');
        }
        // Cambiar valores
        $item->password = \Mia\Auth\Model\MIAUser::encryptPassword($password);
        // Guardar nueva contraseña
        $item->save();
        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}
