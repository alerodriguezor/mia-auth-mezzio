<?php

namespace Mia\Auth\Handler\Notification;

use Mia\Auth\Model\MIANotification;
use Mia\Auth\Repository\MIANotificationRepository;
use Mia\Core\Diactoros\MiaJsonErrorResponse;

/**
 * Description of ReadAllHandler
 * 
 * @OA\Get(
 *     path="/mia-notification/read-all",
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
class ReadAllHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Get current user
        $user = $this->getUser($request);
        // Fetch notification
        $item = MIANotification::where('user_id', $user->id)->update(['is_read' => 1]);
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}