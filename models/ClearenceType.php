<?php

Yii::import('application.models._base.BaseClearenceType');

class ClearenceType extends BaseClearenceType {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'remarks' => 'Remarks',
		);
	}

}

?>