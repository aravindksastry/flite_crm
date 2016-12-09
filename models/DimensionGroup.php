<?php

Yii::import('application.models._base.BaseDimensionGroup');

class DimensionGroup extends BaseDimensionGroup {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'manage' => 'Manage',
			'remarks' => 'Remarks',
		);
	}

}

?>