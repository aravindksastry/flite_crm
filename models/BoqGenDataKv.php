<?php

Yii::import('application.models._base.BaseBoqGenDataKv');

class BoqGenDataKv extends BaseBoqGenDataKv {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'boq_gen_data_id' => 'Boq Gen Data',
			'key_id' => 'Key',
			'value' => 'Value',
			'data' => 'Data',
		);
	}

}

?>