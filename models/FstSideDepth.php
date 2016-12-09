<?php

Yii::import('application.models._base.BaseFstSideDepth');

class FstSideDepth extends BaseFstSideDepth {

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