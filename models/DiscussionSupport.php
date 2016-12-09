<?php

Yii::import('application.models._base.BaseDiscussionSupport');

class DiscussionSupport extends BaseDiscussionSupport {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'glass_top' => 'Glass Top',
			'finish_type_id' => 'Finish Type',
			'data' => 'Data',
		);
	}

}

?>