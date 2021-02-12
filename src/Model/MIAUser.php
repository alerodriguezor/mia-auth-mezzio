<?php namespace Mia\Auth\Model;

/**
 * Description of Model
 * @property int $id User ID
 * @property int $mia_id MIA ID
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
 *  type="bigint(20)",
 *  description=""
 * )
 * @OA\Property(
 *  property="firstname",
 *  type="varchar(100)",
 *  description=""
 * )
 * @OA\Property(
 *  property="lastname",
 *  type="varchar(100)",
 *  description=""
 * )
 * @OA\Property(
 *  property="email",
 *  type="varchar(250)",
 *  description=""
 * )
 * @OA\Property(
 *  property="photo",
 *  type="text",
 *  description=""
 * )
 * @OA\Property(
 *  property="phone",
 *  type="varchar(50)",
 *  description=""
 * )
 * @OA\Property(
 *  property="role",
 *  type="int(2)",
 *  description=""
 * )
 * @OA\Property(
 *  property="status",
 *  type="int(1)",
 *  description=""
 * )
 * @OA\Property(
 *  property="is_notification",
 *  type="int(1)",
 *  description=""
 * )
 * @OA\Property(
 *  property="created_at",
 *  type="datetime",
 *  description=""
 * )
 * @OA\Property(
 *  property="updated_at",
 *  type="datetime",
 *  description=""
 * )
 *
 * @author matiascamiletti
 */
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