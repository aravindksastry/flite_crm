<?php

Yii::import('application.models._base.BaseBoq');

class Boq extends BaseBoq {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'boq_variant_id' => 'Boq Variant',
			'phase_id' => 'Phase',
			'uid' => 'Uid',
			'sys' => 'Sys',
			'mat' => 'Mat',
			's1' => 'S1',
			's2' => 'S2',
			's3' => 'S3',
			'd1' => 'D1',
			'd2' => 'D2',
			'd3' => 'D3',
			'd4' => 'D4',
			'fin' => 'Fin',
			'qty' => 'Qty',
			'u_o_m_id' => 'U O M',
			'a' => 'A',
			'b' => 'B',
			'c' => 'C',
			'd' => 'D',
			'e' => 'E',
			'f' => 'F',
			'g' => 'G',
			'h' => 'H',
			'i' => 'I',
			'j' => 'J',
			'data' => 'Data',
		);
	}

}

?>