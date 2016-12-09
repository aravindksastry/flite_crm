<?php

Yii::import('application.models._base.BaseFreeMetalLegDesign');

class FreeMetalLegDesign extends BaseFreeMetalLegDesign {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'data' => 'Data',
		);
	}

}

?>