<?php

Yii::import('application.models._base.BaseFrameWdt');

class FrameWdt extends BaseFrameWdt {

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