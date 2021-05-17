<?php

namespace Mia\Auth\Handler\NotificationType;

use Mia\Auth\Model\MIANotificationType;
use Mia\Auth\Model\MIAUserNotificationConfig;

class ListHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Get current user
        $user = $this->getUser($request);
        // Get all
        $nots = MIANotificationType::all()->toArray();
        // Get All for user
        $config = MIAUserNotificationConfig::where('user_id', $user->id)->get()->toArray();
        // For each
        for ($i = 0; $i < count($nots); $i++) { 
            for ($j = 0; $j < count($config); $i++) { 
                if($config[$j]['type_id'] == $nots[$i]['id']){
                    $nots[$i]['has_email'] = $config[$j]['has_email'];
                    $nots[$i]['has_sms'] = $config[$j]['has_sms'];
                    $nots[$i]['has_whatsapp'] = $config[$j]['has_whatsapp'];
                    $nots[$i]['has_web'] = $config[$j]['has_web'];
                    $nots[$i]['has_mobile'] = $config[$j]['has_mobile'];
                }
            }
        }
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse($nots);
    }
}