<?php

Yii::import('application.models._base.BaseFstBackDepth');

class FstBackDepth extends BaseFstBackDepth {

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