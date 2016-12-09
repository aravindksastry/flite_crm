<?php

Yii::import('application.models._base.BaseCabinTipType');

class CabinTipType extends BaseCabinTipType {

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