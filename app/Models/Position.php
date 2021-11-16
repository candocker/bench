<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Position extends AbstractModel
{
    protected $table = 'position';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getFocusDatas($sort)
    {
        $position = $this->where(['code' => $sort . 'focus'])->first();
        if (empty($position)) {
            return [];
        }
        $infos = $position->getInfos();
        return $infos;
    }

    public function getRecommendDatas($sort)
    {
        $position = $this->where(['code' => $sort])->first();
        if (empty($position)) {
            return ['navDatas' => []];
        }
        $infos = $position->getInfos();
        return ['navDatas' => $infos];
    }

    public function getInfos()
    {
        $infos = $this->getModelObj('positionInfo')->where('position_code', $this->code)->orderBy('orderlist', 'desc')->get();
        $result = [];
        foreach ($infos as $info) {
            $data = $info->formatInfo();
            $result[] = $data;
        }

        return $result;
    }
}
