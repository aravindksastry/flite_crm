<?php

Yii::import('application.models._base.BaseCubicleCoOrdinate');

class CubicleCoOrdinate extends BaseCubicleCoOrdinate {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'data' => 'Data',
		);
	}

}

?>