<?php

Yii::import('application.models._base.BaseBranch');

class Branch extends BaseBranch {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'organization_id' => 'Organization',
			'city_id' => 'City',
			'locality' => 'Locality',
			'address_line_1' => 'Address Line 1',
			'addeess_line_2' => 'Addeess Line 2',
			'e_mail' => 'E Mail',
			'phone' => 'Phone',
			'under_dealership' => 'Under Dealership',
			'is_internal' => 'Is Internal',
		);
	}

}

?>