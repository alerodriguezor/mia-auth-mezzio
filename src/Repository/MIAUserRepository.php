<?php namespace Mia\Auth\Repository;

use Mia\Auth\Model\MIAUser;

class MIAUserRepository
{
    /**
     * 
     * @param \Mia\Database\Query\Configure $configure
     * @return \Illuminate\Pagination\Paginator
     */
    public static function fetchByConfigure(\Mia\Database\Query\Configure $configure)
    {
        $query = MIAUser::select('mia_user.*');
        
        if(!$configure->hasOrder()){
            $query->orderByRaw('id DESC');
        }
        $search = $configure->getSearch();
        if($search != ''){
            $values = $search . '|' . implode('|', explode(' ', $search));
            $query->whereRaw('(firstname REGEXP ? OR lastname REGEXP ? OR email REGEXP ?)', [$values, $values, $values]);
        }
        
        // Procesar parametros
        $configure->run($query);

        return $query->paginate($configure->getLimit(), ['*'], 'page', $configure->getPage());
    }

    public static function findByID($id)
    {
        return \Mia\Auth\Model\MIAUser::where('id', $id)->first();
    }
    
    public static function findByEmail($email)
    {
        return \Mia\Auth\Model\MIAUser::where('email', $email)->first();
    }
}