<?php

Yii::import('application.models._base.BaseCubicleTable');

class CubicleTable extends BaseCubicleTable {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'cubicle_cluster_id' => 'Cubicle Cluster',
			'include_LH_table' => 'Include Lh Table',
			'include_RH_table' => 'Include Rh Table',
			'understructure_reqd' => 'Understructure Reqd',
			'side_frames_matching' => 'Side Frames Matching',
			'back_frames_matching' => 'Back Frames Matching',
			'table_qty' => 'Table Qty',
			'data' => 'Data',
		);
	}

}

?>