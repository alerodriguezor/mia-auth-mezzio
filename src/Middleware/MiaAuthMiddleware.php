<?php

namespace Mia\Auth\Middleware;
/**
 * Description of MiaAuthMiddleware
 *
 * @author matiascamiletti
 */
abstract class MiaAuthMiddleware extends \Mia\Core\Middleware\MiaBaseMiddleware
{
    /**
     * Obtiene usuario logueado
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Mia\Auth\Model\MIAUser
     */
    protected function getUser(\Psr\Http\Message\ServerRequestInterface $request)
    {
        return $request->getAttribute(\Mia\Auth\Model\MIAUser::class);
    }
}