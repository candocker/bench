<?php

namespace bench\spider\models;

use Yii;
use bench\spider\models\BaseModel;

class TargetModel extends BaseModel
{
	static public $_markDb = 'dbBench';
	static public $_markTable = 'page';

	public static function tableName()
	{
		$table = self::$_markTable;
	    return '{{%' . $table . '}}';
    }

    public static function getDb()
    {
		$db = self::$_markDb;
        return Yii::$app->$db;
    }

	public function getDynamicTable($db, $table)
	{
		self::$_markDb = $db;
		self::$_markTable = $table;
		$model = new self();
		return $model;
	}
}
