<?php

Yii::import('application.models._base.BaseItemApproval');

class ItemApproval extends BaseItemApproval {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'item_id' => 'Item',
			'ready' => 'Ready',
			'approve' => 'Approve',
			'disapprove' => 'Disapprove',
			'remarks' => 'Remarks',
		);
	}

}

?>