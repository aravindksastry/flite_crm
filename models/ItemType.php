<?php

Yii::import('application.models._base.BaseItemType');

class ItemType extends BaseItemType {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'model_class' => 'Model Class',
			'clusterset_class' => 'Clusterset Class',
			'finish_class' => 'Finish Class',
			'filter' => 'Filter',
			'accessory' => 'Accessory',
			'storage' => 'Storage',
			'ohd_storage' => 'Ohd Storage',
			'pedestal' => 'Pedestal',
			'uid_list' => 'Uid List',
			'default_entry' => 'Default Entry',
			'colomn_name' => 'Colomn Name',
		);
	}

}

?>