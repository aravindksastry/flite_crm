<?php

Yii::import('application.models._base.BaseAuthority');

class Authority extends BaseAuthority {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'responsibility_role_id' => 'Responsibility Role',
			'approver_role_id' => 'Approver Role',
		);
	}

}

?>