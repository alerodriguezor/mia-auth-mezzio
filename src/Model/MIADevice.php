<?php

namespace Mia\Auth\Model;

/**
 * Description of MIADevice
 *
 * @author matiascamiletti
 */
class MIADevice extends \Illuminate\Database\Eloquent\Model
{
    const TYPE_ANDROID = 1;
    const TYPE_IOS = 2;
    
    protected $table = 'mia_device';
    
    public $timestamps = false;
}
