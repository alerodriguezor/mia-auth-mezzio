<?php

declare(strict_types=1);

namespace Mia\Auth\Factory;

use Psr\Container\ContainerInterface;

/**
 * Description of AuthHandlerFactory
 *
 * @author matiascamiletti
 */
class AuthHandlerFactory 
{
    public function __invoke(ContainerInterface $container) : \Mia\Auth\Handler\AuthHandler
    {
        // Obtenemos configuracion
        $config = $container->get('config')['mia_auth'];
        // Generamos el handler
        return new \Mia\Auth\Handler\AuthHandler($config);
    }
}