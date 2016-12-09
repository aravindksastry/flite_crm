<?php

Yii::import('application.models._base.BaseCity');

class City extends BaseCity {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'state_id' => 'State',
		);
	}

}

?>