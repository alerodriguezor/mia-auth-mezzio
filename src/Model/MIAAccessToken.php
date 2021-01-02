<?php

namespace Mia\Auth\Model;

/**
 * Description of MIAAccessToken
 *
 * @author matiascamiletti
 */
class MIAAccessToken  extends \Illuminate\Database\Eloquent\Model
{
    const PLATFORM_WEB = 1;
    const PLATFORM_IOS = 2;
    const PLATFORM_ANDROID = 3;
    
    protected $table = 'mia_access_token';
    
    protected $casts = ['device_data' => 'array'];
    
    /**
     * Genera la fecha de expiración del accessToken
     * @return string
     */
    public static function generateExpires()
    {
        $date = new \DateTime();
        $date->add(new \DateInterval('P60D'));
        return $date->format('Y-m-d H:i:s');
    }
    /**
     * Genera el codigo para el AccessToken
     * @return string
     */
    public static function generateAccessToken()
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $randomData = openssl_random_pseudo_bytes(20);
            if ($randomData !== false && strlen($randomData) === 20) {
                return bin2hex($randomData);
            }
        }
        if (function_exists('mcrypt_create_iv')) {
            $randomData = mcrypt_create_iv(20, MCRYPT_DEV_URANDOM);
            if ($randomData !== false && strlen($randomData) === 20) {
                return bin2hex($randomData);
            }
        }
        if (@file_exists('/dev/urandom')) { // Get 100 bytes of random data
            $randomData = file_get_contents('/dev/urandom', false, null, 0, 20);
            if ($randomData !== false && strlen($randomData) === 20) {
                return bin2hex($randomData);
            }
        }
        // Last resort which you probably should just get rid of:
        $randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand() . microtime(true) . uniqid(mt_rand(), true);

        return substr(hash('sha512', $randomData), 0, 40);
    }
}
