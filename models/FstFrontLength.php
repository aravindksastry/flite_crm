<?php

Yii::import('application.models._base.BaseFstFrontLength');

class FstFrontLength extends BaseFstFrontLength {

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