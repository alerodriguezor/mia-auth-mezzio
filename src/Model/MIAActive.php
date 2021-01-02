<?php

namespace Mia\Auth\Model;

/**
 * Description of MIAActive
 *
 * @author matiascamiletti
 */
class MIAActive extends \Illuminate\Database\Eloquent\Model
{
    const STATUS_PENDING = 0;
    const STATUS_USED = 1;
    
    protected $table = 'mia_active';
}
