<?php

namespace Mia\Auth\Handler\User;

use Mia\Auth\Model\MIAUser;

/**
 * Description of FetchHandler
 *
 * @author matiascamiletti
 */
class FetchHandler extends \Mia\Auth\Request\MiaAuthRequestHandler
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
        // Verify has withs in query
        $withs = $this->getParam($request, 'withs', '');
        if($withs != ''){
            // Convert to array
            $with = explode(',', $withs);
            // Search item in DB
            $item = MIAUser::with($with)->where('id', $itemId)->first();
        } else {
            // Buscar si existe el tour en la DB
            $item = MIAUser::find($itemId);
        }
        // verificar si existe
        if($item === null){
            return new \Mia\Core\Diactoros\MiaJsonErrorResponse(1, 'The element is not exist.');
        }
        // Devolvemos respuesta
        return new \Mia\Core\Diactoros\MiaJsonResponse($item->toArray());
    }
}