<?php

namespace Mia\Auth\Handler\Notification;

use Mia\Auth\Model\MIANotification;
use Mia\Auth\Repository\MIANotificationRepository;
use Mia\Core\Diactoros\MiaJsonErrorResponse;

/**
 * Description of ReadHandler
 * 
 * @OA\Get(
 *     path="/mia-notification/read",
 *     summary="Read notification",
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/MIARole")
 *     )
 * )
 *
 * @author matiascamiletti
 */
class ReadHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // get id notification
        $notId = $this->getParam($request, 'id', '0');
        // Get current user
        $user = $this->getUser($request);
        // Fetch notification
        $item = MIANotification::where('user_id', $user->id)->where('id', $notId)->first();
        // Verify if exist
        if($item === null){
            return new MiaJsonErrorResponse(-1, 'Notification not exist');
        }
        // Mark read
        $item->is_read = 1;
        $item->save();
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}