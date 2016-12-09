<?php

Yii::import('application.models._base.BaseDiscussionProfile');

class DiscussionProfile extends BaseDiscussionProfile {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'drawing_path' => 'Drawing Path',
			'data' => 'Data',
		);
	}

}

?>