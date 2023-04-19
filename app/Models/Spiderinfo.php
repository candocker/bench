<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Spiderinfo extends AbstractModel
{
    protected $table = 'spiderinfo';
    protected $guarded = ['id'];

    public function getTargetModel()
    {
        return $this->getModelObj($this->info_db . '-' . $this->info_table);
    }

	/*protected function _afterSaveOpe()
	{
		if (in_array('status', array_keys($changedAttributes)) && $this->status == 100) {
			$this->getPointModel('commonlist')->updateAll(['status' => $this->status], ['spiderinfo_id' => $this->id]);
			$r = $this->getPointModel('commoninfo')->updateAll(['status' => $this->status], ['spiderinfo_id' => $this->id]);
			//$this->getPointMode('commininfo')->updateAll(['status' => $this->status], ['spiderinfo_id' => $this->id]);
		}
		return true;
    }*/
}
