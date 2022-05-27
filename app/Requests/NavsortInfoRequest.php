<?php

declare(strict_types = 1);

namespace ModuleBench\Requests;

class NavsortInfoRequest extends AbstractRequest
{
    protected function _updateRule()
    {
        return [
            //'id' => ['bail', 'required', 'exists'],
            'navsort_code' => ['bail'],
            'info_id' => ['bail'],
            'status' => ['bail'],
        ];
    }

    public function attributes(): array
    {
        return [
            //'name' => '名称',
        ];
    }

    public function messages(): array
    {
        return [
            //'name.required' => '请填写名称',
        ];
    }

    public function filterDirtyData($data)
    {
        if (isset($data['navsort_code']) && is_array($data['navsort_code'])) {
            $data['navsort_code'] = array_pop($data['navsort_code']);
        }
        return $data;
    }
}
