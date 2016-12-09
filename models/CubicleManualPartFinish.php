<?php

Yii::import('application.models._base.BaseCubicleManualPartFinish');

class CubicleManualPartFinish extends BaseCubicleManualPartFinish {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'cubicle_manual_part_id' => 'Cubicle Manual Part',
			'cubicle_finish_id' => 'Cubicle Finish',
			'finish_id' => 'Finish',
			'data' => 'Data',
		);
	}

}

?>