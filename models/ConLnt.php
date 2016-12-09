<?php

Yii::import('application.models._base.BaseConLnt');

class ConLnt extends BaseConLnt {

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