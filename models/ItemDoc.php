<?php

Yii::import('application.models._base.BaseItemDoc');

class ItemDoc extends BaseItemDoc {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'doc_type_id' => 'Doc Type',
			'item_id' => 'Item',
			'doc_name' => 'Doc Name',
			'type' => 'Type',
		);
	}

	public function beforeSave() {
		if (!$this->type) {
			$this->doc_name = CUploadedFile::getInstance($this, 'doc_name');
			if ($this->doc_name)
				$this->type = $this->doc_name->type;
		}
		return parent::beforeSave();
	}

	public function afterSave() {
		if ($this->doc_name && (!file_exists(Yii::app()->getBasePath() . '/data/I' . $this->id))) {
			$this->doc_name->saveAs(Yii::app()->getBasePath() . '/data/I' . $this->id);
		}
		return parent::afterSave();
	}

}

?>