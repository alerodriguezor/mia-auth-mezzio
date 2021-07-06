<?php

/**
 * @see       https://github.com/mezzio/mezzio-authorization for the canonical source repository
 * @copyright https://github.com/mezzio/mezzio-authorization/blob/master/COPYRIGHT.md
 * @license   https://github.com/mezzio/mezzio-authorization/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Mia\Auth;

class ConfigProvider
{
    /**
     * Return the configuration array.
     */
    public function __invoke() : array
    {
        return [
            'dependencies'  => $this->getDependencies()
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'factories' => [
                \Mia\Auth\Handler\AuthHandler::class => \Mia\Auth\Factory\AuthHandlerFactory::class,
                \Mia\Auth\Handler\AuthOptionalHandler::class => \Mia\Auth\Factory\AuthOptionalHandlerFactory::class,
                \Mia\Auth\Handler\LoginHandler::class => \Mia\Auth\Factory\LoginHandlerFactory::class,
                \Mia\Auth\Handler\Social\GoogleSignInHandler::class => \Mia\Auth\Factory\GoogleSignInHandlerFactory::class,
                \Mia\Auth\Handler\Social\PhoneSignInHandler::class => \Mia\Auth\Factory\PhoneSignInHandlerFactory::class,
            ],
        ];
    }
}