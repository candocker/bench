<?php

declare(strict_types = 1);

namespace ModuleBench\Requests;

class SpiderinfoRequest extends AbstractRequest
{
    protected function _updateRule()
    {
        return [
            'id' => ['bail', 'required', 'exists'],
        ];
    }

    public function attributes(): array
    {
        return [
            //'name' => '名称',
        ];
        return [
			[['code', 'name', 'site_code', 'url'], 'required'],
            [['status'], 'default', 'value' => 0],
            [['sort', 'attachment_db', 'attachment_field', 'info_db', 'info_field', 'info_table'], 'safe'],
        ];
    }

    public function messages(): array
    {
        return [
            //'name.required' => '请填写名称',
        ];
    }
}
