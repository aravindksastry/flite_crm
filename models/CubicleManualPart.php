<?php

Yii::import('application.models._base.BaseCubicleManualPart');

class CubicleManualPart extends BaseCubicleManualPart {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'cubicle_table_id' => 'Cubicle Table',
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
			'qty_per_table' => 'Qty Per Table',
			'description' => 'Description',
			'size' => 'Size',
			'data' => 'Data',
		);
	}

}

?>