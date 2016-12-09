<?php

Yii::import('application.models._base.BaseApproval');

class Approval extends BaseApproval {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'enquiry_id' => 'Enquiry',
			'ready' => 'Ready',
			'approve' => 'Approve',
			'disapprove' => 'Disapprove',
			'remarks' => 'Remarks',
		);
	}

}

?>