<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Position extends AbstractModel
{
    protected $table = 'position';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getMobileListDatas($code)
    {
        return $this->getPointDatas($code . 'mobilelist');
    }

    public function getSortFocusDatas($code)
    {
        return $this->getPointDatas($code . 'sortfocus');
    }

    public function getFocusDatas($code)
    {
        return $this->getPointDatas($code . 'focus');
    }

    public function getPointDatas($code)
    {
        $position = $this->where(['code' => $code])->first();
        if (empty($position)) {
            return [];
        }
        $infos = $position->getInfos();
        return $infos;
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
