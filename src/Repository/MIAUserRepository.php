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

    public static function totals($from, $to)
    {
        $row = \Mia\Auth\Model\MIAUser::
                selectRaw('COUNT(*) as total')
                ->whereRaw('DATE(created_at) >= DATE(?) AND DATE(created_at) <= DATE(?)', [$from, $to])
                ->first();
        if($row === null||$row->total == null){
            return 0;
        }
        return $row->total;
    }

    public static function totalsByKey($from, $to, $key, $value)
    {
        $row = \Mia\Auth\Model\MIAUser::
                selectRaw('COUNT(*) as total')
                ->whereRaw('DATE(created_at) >= DATE(?) AND DATE(created_at) <= DATE(?)', [$from, $to])
                ->where($key, $value)
                ->first();
        if($row === null||$row->total == null){
            return 0;
        }
        return $row->total;
    }
}