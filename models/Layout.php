<?php

Yii::import('application.models._base.BaseLayout');

class Layout extends BaseLayout {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'enquiry_id' => 'Enquiry',
			'floor_id' => 'Floor',
			'floor_segment_name' => 'Floor Segment Name',
		);
	}

	public function beforeDelete() {
		foreach (LayoutDoc::model()->findAll('layout_id=' . $this->id) as $rd) {
			$p = Yii::app()->getBasePath() . '/data/L' . $rd->id;
			if (file_exists($p))
				unlink($p);
			$rd->delete();
		}
		return (parent::beforeDelete());
	}

}

?>