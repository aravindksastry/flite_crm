<?php

Yii::import('application.models._base.BaseApprovalType');

class ApprovalType extends BaseApprovalType {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'group_id' => 'Group',
			'authority_id' => 'Authority',
			'responsibility_id' => 'Responsibility',
			'approver_id' => 'Approver',
			'enquiry' => 'Enquiry',
			'item' => 'Item',
			'region' => 'Region',
			'data' => 'Data',
		);
	}

}

?>