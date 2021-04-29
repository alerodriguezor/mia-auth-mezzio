<?php namespace Mia\Auth\Repository;

use \Illuminate\Database\Capsule\Manager as DB;
use Mia\Auth\Model\MIANotification;

class MIANotificationRepository
{
    /**
     * 
     * @param \Mia\Database\Query\Configure $configure
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function fetchByConfigure(\Mia\Database\Query\Configure $configure)
    {
        $query = MIANotification::select('mia_notification.*');
        
        if(!$configure->hasOrder()){
            $query->orderByRaw('id DESC');
        }
        $search = $configure->getSearch();
        if($search != ''){
            //$values = $search . '|' . implode('|', explode(' ', $search));
            //$query->whereRaw('(firstname REGEXP ? OR lastname REGEXP ? OR email REGEXP ?)', [$values, $values, $values]);
        }
        
        // Procesar parametros
        $configure->run($query);

        return $query->paginate($configure->getLimit(), ['*'], 'page', $configure->getPage());
    }

    public static function create($creatorId, $userId, $type, $itemId, $data, $caption = '')
    {
        $row = new MIANotification();
        $row->creator_id = $creatorId;
        $row->user_id = $userId;
        $row->type_id = $type;
        $row->item_id = $itemId;
        $row->data = $data;
        $row->caption = $caption;
        $row->save();
        return $row;
    }
}