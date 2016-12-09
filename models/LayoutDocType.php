<?php

Yii::import('application.models._base.BaseLayoutDocType');

class LayoutDocType extends BaseLayoutDocType {

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