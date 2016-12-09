<?php

Yii::import('application.models._base.BaseFstSideLength');

class FstSideLength extends BaseFstSideLength {

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