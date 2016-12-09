<?php

Yii::import('application.models._base.BaseAddOnTableDpt');

class AddOnTableDpt extends BaseAddOnTableDpt {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
		);
	}

}

?>