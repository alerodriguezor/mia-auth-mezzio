<?php

namespace Mia\Auth\Handler\User;

use Mia\Auth\Model\MIAUser;
use Mia\Auth\Repository\MIAUserRepository;

/**
 * Description of BlockHandler
 * 
 * @OA\Get(
 *     path="/user/block",
 *     summary="User Block Change",
 *     tags={"User"},
 *     @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/MIAUser")
 *     )
 * )
 *
 * @author matiascamiletti
 */
class BlockHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
{
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
        if(intval($this->getParam($request, 'block', '1')) == 1){
            $item->status = MIAUser::STATUS_BLOCKED;
        } else {
            $item->status = MIAUser::STATUS_ACTIVE;
        }
        $item->save();
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse(true);
    }
}