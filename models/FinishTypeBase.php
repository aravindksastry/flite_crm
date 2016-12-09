<?php

Yii::import('application.models._base.BaseFinishTypeBase');

class FinishTypeBase extends BaseFinishTypeBase {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'manage' => 'Manage',
			'data' => 'Data',
		);
	}

}

?>