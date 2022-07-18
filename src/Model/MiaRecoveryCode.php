<?php

namespace Mia\Auth\Model;

/**
 * Description of MiaRecoveryCode
 *
 * @author matiascamiletti
 */
class MiaRecoveryCode extends \Illuminate\Database\Eloquent\Model
{
    const STATUS_PENDING = 0;
    const STATUS_USED = 1;
    
    protected $table = 'mia_recovery_code';
}
