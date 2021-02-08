<?php

namespace App\Models;

use App\Constants\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Transactions extends BaseModel
{
    use HasFactory;
    protected $guarded = ['status'];

    /**
     * @param $id
     * @return mixed
     */
    public static function getById($id)
    {
        return self::where('id', $id)
            ->first();
    }

    /**
     * @param array $params
     * @param int $limit
     * @return mixed
     */
    public static function filter(array $params, $limit = 10)
    {
        $conditions = [];

        foreach ($params as $key => $param) {
            $conditions[] = [$key, '=', $param];
        }
        return self::where($conditions)
            ->paginate($limit);
    }

    public static function getMetrics()
    {
        return DB::table('transactions', 't')
            ->select([
                DB::raw('t.status, COUNT(t.status) as count')
            ])
            ->groupBy('status')
            ->get()
            ->toArray();

    }
}
