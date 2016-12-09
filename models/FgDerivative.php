<?php

Yii::import('application.models._base.BaseFgDerivative');

class FgDerivative extends BaseFgDerivative {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'pl_id' => 'Pl',
			'd1' => 'D1',
			'd2' => 'D2',
			'd3' => 'D3',
			'd4' => 'D4',
			'qty' => 'Qty',
			'fin_str' => 'Fin Str',
		);
	}

}

?>