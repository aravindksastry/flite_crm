<?php

Yii::import('application.models._base.BaseBoardRoomTableDpt');

class BoardRoomTableDpt extends BaseBoardRoomTableDpt {

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