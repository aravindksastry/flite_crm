<?php

Yii::import('application.models._base.BaseFstBackLength');

class FstBackLength extends BaseFstBackLength {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
		);
	}

}

?>