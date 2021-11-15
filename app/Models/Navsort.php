<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Navsort extends AbstractModel
{
    protected $table = 'navsort';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getCategoryDatas()
    {
        $datas = $this->where(['parent_code' => '', 'status' => 1])->get()->toArray();
        return array_chunk($datas, 4, true);
    }
}
