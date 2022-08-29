<?php

namespace Mia\Auth\Handler\NotificationType;

use Mia\Auth\Model\MIANotificationType;
use Mia\Auth\Model\MIAUserNotificationConfig;

class SaveHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Get current user
        $user = $this->getUser($request);
        // Get param
        $typeId = $this->getParam($request, 'type_id', 0);
        // Get All for user
        $config = MIAUserNotificationConfig::where('user_id', $user->id)->where('type_id', $typeId)->first();
        if($config === null){
            $config = new MIAUserNotificationConfig();
            $config->user_id = $user->id;
            $config->type_id = $typeId;
        }
        $config->has_email = $this->getParam($request, 'has_email', 1);
        $config->has_sms = $this->getParam($request, 'has_sms', 1);
        $config->has_whatsapp = $this->getParam($request, 'has_whatsapp', 1);
        $config->has_web = $this->getParam($request, 'has_web', 1);
        $config->has_mobile = $this->getParam($request, 'has_mobile', 1);
        $config->save();
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}