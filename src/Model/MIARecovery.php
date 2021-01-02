<?php

namespace Mia\Auth\Model;

/**
 * Description of MIARecovery
 *
 * @author matiascamiletti
 */
class MIARecovery extends \Illuminate\Database\Eloquent\Model
{
    const STATUS_PENDING = 0;
    const STATUS_USED = 1;
    
    protected $table = 'mia_recovery';
}
