<?php

Yii::import('application.models._base.BaseBoqVariantList');

class BoqVariantList extends BaseBoqVariantList {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'unique_id' => 'Unique',
			'system_id' => 'System',
			'material_id' => 'Material',
			's1' => 'S1',
			's2' => 'S2',
			's3' => 'S3',
			'd1' => 'D1',
			'd2' => 'D2',
			'd3' => 'D3',
			'd4' => 'D4',
			'fin' => 'Fin',
			'data' => 'Data',
			'department_id' => 'Department',
		);
	}

}

?>