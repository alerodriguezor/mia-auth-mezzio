<?php namespace Mia\Auth\Model;

/**
 * Description of Model
 * @property int $id ID
 * @property int $user_id User ID
 * @property string $code Code
 * @property int $status Status
 *
 * @OA\Schema()
 * @OA\Property(
 *  property="id",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="user_id",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="code",
 *  type="string",
 *  description=""
 * )
 * @OA\Property(
 *  property="status",
 *  type="integer",
 *  description=""
 * )
 *
 * @author matiascamiletti
 */
class MiaUserCode extends \Illuminate\Database\Eloquent\Model
{
    const STATUS_PENDING = 0;
    const STATUS_USED = 1;
    const STATUS_EXPIRED = 2;

    protected $table = 'mia_user_code';
}