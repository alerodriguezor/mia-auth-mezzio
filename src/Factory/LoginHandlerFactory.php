<?php

declare(strict_types=1);

namespace Mia\Auth\Factory;

use Psr\Container\ContainerInterface;

/**
 * Description of AuthHandlerFactory
 *
 * @author matiascamiletti
 */
class LoginHandlerFactory 
{
    public function __invoke(ContainerInterface $container) : \Mia\Auth\Handler\LoginHandler
    {
        // Obtenemos configuracion
        $config = $container->get('config')['mia_auth'];
        // Generamos el handler
        return new \Mia\Auth\Handler\LoginHandler($config);
    }
}