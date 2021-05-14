<?php namespace Mia\Auth\Model;

/**
 * Description of Model
 * @property int $id Role ID
 * @property int $role_id Role ID
 * @property int $permission_id Role ID
 * @property int $type Role ID
 *
 * @OA\Schema()
 * @OA\Property(
 *  property="id",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="role_id",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="permission_id",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="type",
 *  type="integer",
 *  description=""
 * )
 *
 * @author matiascamiletti
 */
class MIARoleAccess extends \Illuminate\Database\Eloquent\Model
{
    const TYPE_ALLOW = 0;
    const TYPE_DENY = 1;

    protected $table = 'mia_role_access';
}