<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MailList extends BaseModel
{
    use HasFactory;
    protected $guarded = [];


    public static function filter(array $params): Collection
    {
        $conditions = [];

        if(!empty($params['name'])) {
            $conditions[] = [ 'ml.name', '=', $params['name'] ];
        }

        if(!empty($params['email'])) {
            $conditions[] = [ 'ml.email', '=', $params['email'] ];
        }

        if(!empty($params['mail_list_group'])) {
            $conditions[] = [ 'mlg.name', '=', $params['mail_list_group'] ];
        }

        if(!empty($param['status'])) {
            $conditions[] = [ 'ml.status', '=', $param['status'] ];
        }


        return DB::table('mail_lists', 'ml')
            ->leftJoin('mail_list_groups as mlg', 'ml.mail_list_group_id', '=', 'mlg.id')
            ->where($conditions)
            ->get([
                'ml.id',
                'ml.name',
                'ml.email',
                'ml.status',
                'mlg.name AS mail_list_group'
            ]);
    }

}
