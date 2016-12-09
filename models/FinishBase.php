<?php

Yii::import('application.models._base.BaseFinishBase');

class FinishBase extends BaseFinishBase {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'finish_type_base_id' => 'Finish Type Base',
			'vendor_id' => 'Vendor',
			'series_id' => 'Series',
			'grain_direction_horizontal' => 'Grain Direction Horizontal',
			'grain_direction_vertical' => 'Grain Direction Vertical',
			'display_color' => 'Display Color',
			'data' => 'Data',
		);
	}

	public function getFin($fnum) {
		if (!$fnum)
			return '-';
		if (!is_numeric($fnum[0]))
			return $fnum;
		if (!(int) $fnum[0])
			return '-';
		$fin = explode('-', $fnum);
		$ft = FinishType::model()->findByPk($fin[0]);
		if (!$ft)
			return $fnum;
		$ffmt = explode(',', $ft->manage);
		$cnt = 1;
		/* TOP: ? Sides: ? Facia: ? Back: ? Handle: ?,1,2,3,4,5 */
		while ($cnt < count($ffmt)) {
			$ndx = strpos($ffmt[0], '?');
			$fbndx = $this->findByPk($fin[$ffmt[$cnt]]);
			$ffmt[0] = substr($ffmt[0], 0, $ndx) . ($fbndx ? $fbndx->name : '?') . substr($ffmt[0], $ndx + 1);
			$cnt++;
		}
		return $ffmt[0];
	}

}

?>