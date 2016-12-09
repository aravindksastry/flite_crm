<?php

Yii::import('application.models._base.BaseCubicleElevation');

class CubicleElevation extends BaseCubicleElevation {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'cubicle_id' => 'Cubicle',
			'cubicle_finish_id' => 'Cubicle Finish',
			'facia_id' => 'Facia',
			'data' => 'Data',
		);
	}

	public function afterSave() {
		if ($this->isNewRecord) {
			foreach ($this->cubicle->cubicleFinishes as $val) {
				/* @var $val CubicleElevation */
				$cef = new CubicleElevationFin;
				$cef->cubicle_finish_id = $val->id;
				$cef->cubicle_elevation_id = $this->id;
				$cef->name = $val->name . ' | ' . $this->name;
				$cef->save();
			}
		}
		return (parent::afterSave());
	}

	public function beforeDelete() {
		CubicleElevationFin::model()->deleteAll('cubicle_elevation_id=' . $this->id);
		return (parent::beforeDelete());
	}

}

?>