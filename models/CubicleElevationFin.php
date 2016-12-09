<?php

Yii::import('application.models._base.BaseCubicleElevationFin');

class CubicleElevationFin extends BaseCubicleElevationFin {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'cubicle_finish_id' => 'Cubicle Finish',
			'cubicle_elevation_id' => 'Cubicle Elevation',
			'addon2_finish_id' => 'Addon2 Finish',
			'addon1_finish_id' => 'Addon1 Finish',
			'top_tile_finish_id' => 'Top Tile Finish',
			'top_split_tile_finish_id' => 'Top Split Tile Finish',
			'mid_tile_finish_id' => 'Mid Tile Finish',
			'mid_split_tile_finish_id' => 'Mid Split Tile Finish',
			'rwa_finish_id' => 'Rwa Finish',
			'band_finish_id' => 'Band Finish',
			'rwb_finish_id' => 'Rwb Finish',
			'bot_finish_id' => 'Bot Finish',
			'bot_split_finish_id' => 'Bot Split Finish',
			'sk_finish_id' => 'Sk Finish',
			'data' => 'Data',
		);
	}

}

?>