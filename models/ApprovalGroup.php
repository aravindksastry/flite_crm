<?php

Yii::import('application.models._base.BaseApprovalGroup');

class ApprovalGroup extends BaseApprovalGroup {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
		);
	}

}

?>