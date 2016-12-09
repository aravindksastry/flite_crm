<?php

Yii::import('application.models._base.BaseConDpt');

class ConDpt extends BaseConDpt {

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