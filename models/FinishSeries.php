<?php

Yii::import('application.models._base.BaseFinishSeries');

class FinishSeries extends BaseFinishSeries {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'finish_vendor_id' => 'Finish Vendor',
			'data' => 'Data',
		);
	}

}

?>