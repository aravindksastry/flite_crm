<?php

Yii::import('application.models._base.BaseAutogen');

class Autogen extends BaseAutogen {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'item_type_id' => 'Item Type',
			'uid' => 'Uid',
			'eval1' => 'Eval1',
			'eval2' => 'Eval2',
			'eval3' => 'Eval3',
			'eval4' => 'Eval4',
			'eval5' => 'Eval5',
			'eval6' => 'Eval6',
			'eval7' => 'Eval7',
			's' => 'S',
			's_src' => 'S Src',
			's_cond' => 'S Cond',
			'm' => 'M',
			'm_src' => 'M Src',
			'm_cond' => 'M Cond',
			's1' => 'S1',
			's1_src' => 'S1 Src',
			's1_cond' => 'S1 Cond',
			's2' => 'S2',
			's2_src' => 'S2 Src',
			's2_cond' => 'S2 Cond',
			's3' => 'S3',
			's3_src' => 'S3 Src',
			's3_cond' => 'S3 Cond',
			'd1_src' => 'D1 Src',
			'd1_mod' => 'D1 Mod',
			'd1_cond' => 'D1 Cond',
			'd2_src' => 'D2 Src',
			'd2_mod' => 'D2 Mod',
			'd2_cond' => 'D2 Cond',
			'd3_src' => 'D3 Src',
			'd3_mod' => 'D3 Mod',
			'd3_cond' => 'D3 Cond',
			'd4_src' => 'D4 Src',
			'd4_mod' => 'D4 Mod',
			'd4_cond' => 'D4 Cond',
			'fin' => 'Fin',
			'qty' => 'Qty',
			'qty_mod' => 'Qty Mod',
			'remarks' => 'Remarks',
		);
	}

}

?>