<?php

Yii::import('application.models._base.BaseContact');

class Contact extends BaseContact {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'organization_id' => 'Organization',
			'branch_id' => 'Branch',
			'enquiry_id' => 'Enquiry',
			'person_name' => 'Person Name',
			'mobile_number' => 'Mobile Number',
			'e_mail' => 'E Mail',
		);
	}

}

?>