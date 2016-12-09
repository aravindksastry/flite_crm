<?php

Yii::import('application.models._base.BaseBoqGenData');

class BoqGenData extends BaseBoqGenData {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'name' => 'Name',
			'short_hand' => 'Short Hand',
			'default_value' => 'Default Value',
			'manage_data_table' => 'Manage Data Table',
			'manage_key_colomn' => 'Manage Key Colomn',
			'manage_value_colomn' => 'Manage Value Colomn',
			'key_part_group_id' => 'Key Part Group',
			'value_part_group_id' => 'Value Part Group',
			'data' => 'Data',
		);
	}

}

?>