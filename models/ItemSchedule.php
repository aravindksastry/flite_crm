<?php

Yii::import('application.models._base.BaseItemSchedule');

class ItemSchedule extends BaseItemSchedule {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'progress_id' => 'Progress',
			'item_id' => 'Item',
			'remarks' => 'Remarks',
		);
	}

}

?>