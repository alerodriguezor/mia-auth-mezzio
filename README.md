# AgencyCoda Authentication Mezzio

1. Incluir libreria:
```bash
composer require mobileia/mia-core-mezzio
composer require mobileia/mia-auth-mezzio
```
2. Incluir configuración en el archivo: "config/config.php"
```php
// Configurar Modulo Mobileia Auth
\Mia\Core\Auth\Config\ConfigProvider::class,

// Default App module config
//App\ConfigProvider::class,
```
3. Agregar validación a un router:
```php
$app->route('/api/home', [
        \Mia\Auth\Handler\AuthHandler::class,
        App\Handler\HomePageHandler::class], ['GET', 'POST'], 'home');
```
4. Obtener datos del usuario en el handler:
```php
$user = $request->getAttribute(\Mia\Auth\Model\MIAUser::class);
```
5. Activar Autenticación interna, agregando las rutas:
```php
    /** AUTHENTICATION **/
    $app->route('/mia-auth/login', [Mia\Expressive\Auth\Handler\LoginInternalHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_auth.login');
    $app->route('/mia-auth/register', [\Mia\Mail\Handler\SendgridHandler::class, new \Mia\Auth\Handler\RegisterInternalHandler(true)], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_auth.register');
    $app->route('/mia-auth/update-profile', [\Mia\Auth\Handler\AuthHandler::class, Mia\Auth\Handler\UpdateProfileHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_auth.update-profile');
    $app->route('/mia-auth/recovery', [\Mia\Mail\Handler\SendgridHandler::class, Mia\Auth\Handler\MiaRecoveryHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_auth.recovery');
    $app->route('/mia-auth/change-password-recovery', [Mia\Auth\Handler\MiaPasswordRecoveryHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_auth.change-password-recovery');
    $app->route('/mia-auth/me', [\Mia\Auth\Handler\AuthInternalHandler::class, Mia\Auth\Handler\FetchProfileHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_auth.me');
    

    $app->route('/mia-auth/login-with-google', [Mia\Auth\Handler\Social\GoogleSignInHandler::class], ['POST', 'OPTIONS', 'HEAD'], 'mia_auth.login-with-gogle');
    $app->route('/mia-auth/apple-signin', [Mia\Auth\Handler\AppleSignInHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_auth.apple-signin');
    $app->route('/mia-auth/register-device', [\Mia\Auth\Handler\AuthInternalHandler::class, Mia\Auth\Handler\RegisterDeviceHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_auth.register-device');

    $app->route('/mia-auth/role/list', [Mia\Auth\Handler\Role\ListHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_auth.role-list');

    $app->route('/mia-notification/list', [\Mia\Auth\Handler\AuthHandler::class, Mia\Auth\Handler\Notification\ListHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_notification.list');
    $app->route('/mia-notification/read', [\Mia\Auth\Handler\AuthHandler::class, Mia\Auth\Handler\Notification\ReadHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_notification.read');
    $app->route('/mia-notification/read-all', [\Mia\Auth\Handler\AuthHandler::class, Mia\Auth\Handler\Notification\ReadAllHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia_notification.read-all');

    $app->route('/user/list', [\Mia\Auth\Handler\AuthHandler::class, new \Mia\Auth\Middleware\MiaRoleAuthMiddleware([MIAUser::ROLE_ADMIN]), \Mia\Auth\Handler\User\ListHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'user.list');
    $app->route('/user/block', [\Mia\Auth\Handler\AuthHandler::class, new \Mia\Auth\Middleware\MiaRoleAuthMiddleware([MIAUser::ROLE_ADMIN]), \Mia\Auth\Handler\User\BlockHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'user.block');
```

# Login con Google (with Firebase)
1. Activar Authentication en firebase
2. Agregar dominio personalizado (Opcional)
3. Activar login con Google
4. Ingresar a cuentas de servicios en firebase y generar nueva clave
5. Guardar clave dentro del repositorio de la API
6. Configurar archivo config.