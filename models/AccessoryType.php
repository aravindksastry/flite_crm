<?php

Yii::import('application.models._base.BaseAccessoryType');

class AccessoryType extends BaseAccessoryType {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'finish_type_id' => 'Finish Type',
			'data' => 'Data',
		);
	}

}

?>