<?php

Yii::import('application.models._base.BaseAssociatedFg');

class AssociatedFg extends BaseAssociatedFg {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'pl_id' => 'Pl',
			'associated_fg_id' => 'Associated Fg',
			'fg_derivative_id' => 'Fg Derivative',
		);
	}

}

?>