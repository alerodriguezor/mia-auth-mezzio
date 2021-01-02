<?php namespace Mia\Auth\Model;

class MIAUser extends \Illuminate\Database\Eloquent\Model
{
    const ROLE_ADMIN = 1;
    const ROLE_GENERAL = 0;

    protected $table = 'mia_user';
    /**
     * Campos que se ocultan al obtener los registros
     * @var array
     */
    protected $hidden = ['deleted', 'password'];
    
    /**
     * 
     * @param string $password
     * @return string
     */
    public static function encryptPassword($password)
    {
        $bcrypt = new \Laminas\Crypt\Password\Bcrypt();
        $bcrypt->setCost(10);
        return $bcrypt->create($password);
    }
    /**
     * Valida si el password es correcto
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    public static function verifyPassword($password, $hash)
    {
        $bcrypt = new \Laminas\Crypt\Password\Bcrypt();
        $bcrypt->setCost(10);
        return $bcrypt->verify($password, $hash);
    }
}