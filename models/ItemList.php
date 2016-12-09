<?php

Yii::import('application.models._base.BaseItemList');

class ItemList extends BaseItemList {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'item_type' => 'Item Type',
			'uid' => 'Uid',
			's' => 'S',
			'm' => 'M',
			's1' => 'S1',
			's2' => 'S2',
			's3' => 'S3',
			'd1' => 'D1',
			'd2' => 'D2',
			'd3' => 'D3',
			'd4' => 'D4',
			'fin' => 'Fin',
			'qty' => 'Qty',
			'rem_s' => 'Rem S',
			'rem_m' => 'Rem M',
			'rem_s1' => 'Rem S1',
			'rem_s2' => 'Rem S2',
			'rem_s3' => 'Rem S3',
			'rem_d1' => 'Rem D1',
			'rem_d2' => 'Rem D2',
			'rem_d3' => 'Rem D3',
			'rem_d4' => 'Rem D4',
			'condition' => 'Condition',
			'remarks' => 'Remarks',
			'c1' => 'C1',
			'c2' => 'C2',
			'c3' => 'C3',
			'c4' => 'C4',
		);
	}

	public function beforeSave() {
		if (!strlen($this->name))
			$this->name = $this->u->name;
		return parent::beforeSave();
	}

}

?>