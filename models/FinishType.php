<?php

Yii::import('application.models._base.BaseFinishType');

class FinishType extends BaseFinishType {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'manage' => 'Manage',
			'finish_type1_id' => 'Finish Type1',
			'finish_type2_id' => 'Finish Type2',
			'finish_type3_id' => 'Finish Type3',
			'finish_type4_id' => 'Finish Type4',
			'finish_type5_id' => 'Finish Type5',
			'finish_type6_id' => 'Finish Type6',
			'finish_type7_id' => 'Finish Type7',
			'finish_type8_id' => 'Finish Type8',
			'finish_type9_id' => 'Finish Type9',
			'finish_type10_id' => 'Finish Type10',
			'finish_type11_id' => 'Finish Type11',
			'finish_type12_id' => 'Finish Type12',
			'data' => 'Data',
		);
	}

}

?>