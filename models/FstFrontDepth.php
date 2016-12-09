<?php

Yii::import('application.models._base.BaseFstFrontDepth');

class FstFrontDepth extends BaseFstFrontDepth {

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