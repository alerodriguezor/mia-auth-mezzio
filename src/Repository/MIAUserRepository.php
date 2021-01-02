<?php namespace Mia\Auth\Repository;

class MIAUserRepository
{
    public static function findByID($id)
    {
        return \Mia\Auth\Model\MIAUser::where('id', $id)->first();
    }
    
    public static function findByEmail($email)
    {
        return \Mia\Auth\Model\MIAUser::where('email', $email)->first();
    }
}