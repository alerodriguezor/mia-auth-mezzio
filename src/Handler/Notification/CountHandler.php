<?php

namespace Mia\Auth\Handler\Notification;

use Mia\Auth\Model\MIANotification;

/**
 * Description of ReadHandler
 * 
 * @OA\Get(
 *     path="/mia-notification/count",
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
class CountHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Get current user
        $user = $this->getUser($request);
        // Fetch notification
        $items = MIANotification::where('user_id', $user->id)->where('is_read', 0)->get();
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse(['total' => $items->count()]);
    }
}