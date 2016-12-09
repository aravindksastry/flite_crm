<?php

Yii::import('application.models._base.BaseCabinFurnitureArrangement');

class CabinFurnitureArrangement extends BaseCabinFurnitureArrangement {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'data' => 'Data',
			'main_unit' => 'Main Unit',
			'side_unit' => 'Side Unit',
			'back_unit' => 'Back Unit',
		);
	}

}

?>