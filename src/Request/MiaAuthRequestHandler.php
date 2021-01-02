<?php namespace Mia\Auth\Request;


/**
 * Description of MiaAuthRequestHandler
 *
 * @author matiascamiletti
 */
abstract class MiaAuthRequestHandler extends \Mia\Core\Request\MiaRequestHandler
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
