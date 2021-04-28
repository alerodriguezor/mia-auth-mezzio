<?php namespace Mia\Auth\Model;

/**
 * Description of Model
 * @property int $id User ID
 * @property string $firstname Description for variable
 * @property string $lastname Description for variable
 * @property string $email Description for variable
 * @property string $photo URL of photo
 * @property mixed $phone Description for variable
 * @property int $role Description for variable
 * @property int $status Description for variable
 * @property int $is_notification Description for variable
 * @property mixed $created_at Description for variable
 * @property mixed $updated_at Description for variable
 *
 * @OA\Schema()
 * @OA\Property(
 *  property="id",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="firstname",
 *  type="string",
 *  description=""
 * )
 * @OA\Property(
 *  property="lastname",
 *  type="string",
 *  description=""
 * )
 * @OA\Property(
 *  property="email",
 *  type="string",
 *  description=""
 * )
 * @OA\Property(
 *  property="photo",
 *  type="string",
 *  description=""
 * )
 * @OA\Property(
 *  property="phone",
 *  type="string",
 *  description=""
 * )
 * @OA\Property(
 *  property="role",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="status",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="is_notification",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="created_at",
 *  type="date",
 *  description=""
 * )
 * @OA\Property(
 *  property="updated_at",
 *  type="date",
 *  description=""
 * )
 *
 * @author matiascamiletti
 */
class MIAUser extends \Illuminate\Database\Eloquent\Model
{
    const ROLE_ADMIN = 1;
    const ROLE_GENERAL = 0;

    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;

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

    /**
     * 
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        
        static::addGlobalScope('exclude', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->where('mia_user.deleted', 0);
        });
    }
}