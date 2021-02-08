<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    public function edit($data)
    {
        foreach ($data as $key => $item) {
            if(empty($data[$key]))
                continue;
            $this->{$key} = $item;
        }

        $this->update();
    }

    public static function filter(array $params, $limit)
    {
        $conditions = [];

        foreach ($params as $key => $param) {
            $conditions[] = [$key, '=', $param];
        }
        return self::where($conditions)
            ->paginate($limit);
    }
}
