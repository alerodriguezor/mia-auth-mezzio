<?php

namespace Mia\Auth\Handler\Notification;

use Mia\Auth\Repository\MIANotificationRepository;

/**
 * Description of ListHandler
 * 
 * @OA\Get(
 *     path="/mia-notification/list",
 *     summary="Get all notifications",
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/MIARole")
 *     )
 * )
 *
 * @author matiascamiletti
 */
class ListHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Get current user
        $user = $this->getUser($request);
        // Configurar query
        $configure = new \Mia\Database\Query\Configure($this, $request);
        $configure->addWhere('user_id', $user->id);
        // Obtenemos información
        $rows = MIANotificationRepository::fetchByConfigure($configure);
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse($rows->toArray());
    }
}