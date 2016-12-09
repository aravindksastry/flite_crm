<?php

Yii::import('application.models._base.BaseFloor');

class Floor extends BaseFloor {

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