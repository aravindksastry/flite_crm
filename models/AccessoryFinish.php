<?php

Yii::import('application.models._base.BaseAccessoryFinish');

class AccessoryFinish extends BaseAccessoryFinish {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'accessory_id' => 'Accessory',
			'item_id' => 'Item',
			'design_finish_id' => 'Design Finish',
			'finish_id' => 'Finish',
			'data' => 'Data',
		);
	}

	public function getFinish() {
		return array(
			'acc' => array($this->accessory->id => $this->finish->name),
		);
	}
	
	public function getFinishID() {
		return array(
			'acc' => array($this->accessory->id => $this->finish_id ? $this->finish->getFinID():'0-0-0-0-0-0-0-0-0-0-0-0-0'),
		);
	}

	public function afterSave() {
		if ($this->design_finish_id && Item::model()->findByPk($this->item_id)->type->finish_class === 'WorkstationFinish')
			WorkstationFinish::model()->findByPk($this->design_finish_id)->save();
		return (parent::afterSave());
	}

}

?>