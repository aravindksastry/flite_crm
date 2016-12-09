<?php

Yii::import('application.models._base.BaseDbUser');

class DbUser extends BaseDbUser {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'e_mail' => 'E Mail',
			'mobile' => 'Mobile',
			'branch_id' => 'Branch',
			'username' => 'Username',
			'password' => 'Password',
			'roles' => 'Roles',
			'role_id' => 'Role',
		);
	}
	
	public function beforeSave() {
		//update as required for featherlite
		if (strpos($this->password, '***') !== FALSE)
			$this->password = md5(substr(strrchr($this->password, '*'), 1));
		return parent::beforeSave();
	}

}

?>