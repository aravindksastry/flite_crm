<?php

Yii::import('application.models._base.BaseFinishVendor');

class FinishVendor extends BaseFinishVendor {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'organization_id' => 'Organization',
			'data' => 'Data',
		);
	}

}

?>