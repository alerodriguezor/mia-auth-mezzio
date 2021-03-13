<?php namespace Mia\Auth\Model;

/**
 * Description of Model
 * @property int $id Notification ID
 * @property string $title Title of role
 *
 * @OA\Schema()
 * @OA\Property(
 *  property="id",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="title",
 *  type="string",
 *  description=""
 * )
 *
 * @author matiascamiletti
 */
class MIANotification extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'mia_notification';
}