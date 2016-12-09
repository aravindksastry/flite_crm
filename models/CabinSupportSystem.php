<?php

Yii::import('application.models._base.BaseCabinSupportSystem');

class CabinSupportSystem extends BaseCabinSupportSystem {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'applicable_cubicle' => 'Applicable Cubicle',
			'finish_type_id' => 'Finish Type',
			'return_finish_type_id' => 'Return Finish Type',
			'data' => 'Data',
		);
	}

}

?>