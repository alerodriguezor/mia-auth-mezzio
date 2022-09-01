<?php

namespace Mia\Auth\Handler;

/**
 * Description of RegisterDeviceHandler
 *
 * @author matiascamiletti
 */
class RegisterDeviceHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener token
        $token = $this->getParam($request, 'token', '');
        $type = $this->getParam($request, 'type', '0');
        // Obtener usuario
        $user = $this->getUser($request);
        // Registrar nuevo token
        $device = \Mia\Auth\Model\MIADevice::where('user_id', $user->id)->where('type', $type)->first();
        if($device === null){
            $device = new \Mia\Auth\Model\MIADevice();
            $device->user_id = $user->id;
        }
        $device->device_type = $type;
        $device->device_token = $token;
        $device->save();
        // Devolvemos datos del usuario
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}