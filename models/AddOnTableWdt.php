<?php

Yii::import('application.models._base.BaseAddOnTableWdt');

class AddOnTableWdt extends BaseAddOnTableWdt {

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