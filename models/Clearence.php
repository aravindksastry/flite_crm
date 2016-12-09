<?php

Yii::import('application.models._base.BaseClearence');

class Clearence extends BaseClearence {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'enquiry_id' => 'Enquiry',
			'offer_clearence' => 'Offer Clearence',
			'remarks' => 'Remarks',
		);
	}

}

?>