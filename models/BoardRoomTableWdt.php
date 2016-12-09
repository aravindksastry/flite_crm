<?php

Yii::import('application.models._base.BaseBoardRoomTableWdt');

class BoardRoomTableWdt extends BaseBoardRoomTableWdt {

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