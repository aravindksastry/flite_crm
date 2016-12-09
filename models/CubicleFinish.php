<?php

Yii::import('application.models._base.BaseCubicleFinish');

class CubicleFinish extends BaseCubicleFinish {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'cubicle_id' => 'Cubicle',
			'table_top_finish_id' => 'Table Top Finish',
			'front_understructure_finish_id' => 'Front Understructure Finish',
			'side_understructure_finish_id' => 'Side Understructure Finish',
			'other_understructure_finish_id' => 'Other Understructure Finish',
			'frame_pc_finish_id' => 'Frame Pc Finish',
			'front_modesty_finish_id' => 'Front Modesty Finish',
			'side_modesty_finish_id' => 'Side Modesty Finish',
			'back_modesty_finish_id' => 'Back Modesty Finish',
			'front_cable_access_finish_id' => 'Front Cable Access Finish',
			'front_cable_carrier_finish_id' => 'Front Cable Carrier Finish',
			'front_cable_entry_finish_id' => 'Front Cable Entry Finish',
			'side_cable_access_finish_id' => 'Side Cable Access Finish',
			'side_cable_carrier_finish_id' => 'Side Cable Carrier Finish',
			'side_cable_entry_finish_id' => 'Side Cable Entry Finish',
			'back_cable_access_finish_id' => 'Back Cable Access Finish',
			'back_cable_carrier_finish_id' => 'Back Cable Carrier Finish',
			'back_cable_entry_finish_id' => 'Back Cable Entry Finish',
			'side_storage_finish_id' => 'Side Storage Finish',
			'back_storage_finish_id' => 'Back Storage Finish',
                        'perform_leg_finish' => 'Desking Leg Finish',
			'data' => 'Data',
		);
	}

	public function afterSave() {
		if ($this->isNewRecord) {
			foreach ($this->cubicle->cubicleElevations as $val) {
				/* @var $val CubicleElevation */
				$cef = new CubicleElevationFin;
				$cef->cubicle_finish_id = $this->id;
				$cef->cubicle_elevation_id = $val->id;
				$cef->name = $this->name . ' | ' . $val->name;
				$cef->save();
			}
		}
		return (parent::afterSave());
	}

	public function beforeDelete() {
		StorageFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->cubicle->item_id);
		AccessoryFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->cubicle->item_id);
		OverHeadStorage::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->cubicle->item_id);
		PedestalFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->cubicle->item_id);
		CubicleElevationFin::model()->deleteAll('cubicle_finish_id=' . $this->id);
		return (parent::beforeDelete());
	}

	public function getFinish() {
		$fin = array(
			'cef' => array(),
			'tbl' => $this->table_top_finish_id ? $this->tableTopFinish->name : '???',
			'sgf' => $this->other_understructure_finish_id ? $this->otherUnderstructureFinish->name : '???',
			'sup' => array(
				'f' => $this->front_understructure_finish_id ? $this->frontUnderstructureFinish->name : '???',
				's' => $this->side_understructure_finish_id ? $this->sideUnderstructureFinish->name : '???',
				'b' => $this->other_understructure_finish_id ? $this->otherUnderstructureFinish->name : '???'),
			'pm' => array(
				'f' => $this->front_modesty_finish_id ? $this->frontModestyFinish->name : '???',
				's' => $this->side_modesty_finish_id ? $this->sideModestyFinish->name : '???',
				'b' => $this->back_modesty_finish_id ? $this->backModestyFinish->name : '???'),
			'ca' => array(
				'f' => $this->front_cable_access_finish_id ? $this->frontCableAccessFinish->name : '???',
				's' => $this->side_cable_access_finish_id ? $this->sideCableAccessFinish->name : '???',
				'b' => $this->back_cable_access_finish_id ? $this->backCableAccessFinish->name : '???',
			),
			'ch' => array(
				'f' => $this->front_cable_carrier_finish_id ? $this->frontCableCarrierFinish->name : '???',
				's' => $this->side_cable_carrier_finish_id ? $this->sideCableCarrierFinish->name : '???',
				'b' => $this->back_cable_carrier_finish_id ? $this->backCableCarrierFinish->name : '???',),
			'ce' => array(
				'f' => $this->front_cable_entry_finish_id ? $this->frontCableEntryFinish->name : '???',
				's' => $this->side_cable_entry_finish_id ? $this->sideCableEntryFinish->name : '???',
				'b' => $this->back_cable_entry_finish_id ? $this->backCableEntryFinish->name : '???',),
			'st' => array(
				's' => $this->side_storage_finish_id ? $this->sideStorageFinish->name : '???',
				'b' => $this->back_storage_finish_id ? $this->backStorageFinish->name : '???'
			),
			'sf' => $this->frame_pc_finish_id ? $this->framePcFinish->name : '???',
                        'per_leg' => array(
                            'side' => $this->perform_leg_finish ? $this->performLegFinish->name : '???',
                            'cen' => $this->perform_leg_finish ? $this->performLegFinish->name : '???',
                            ),
			/*'acc' => array(),
			'ohd' => array(),
			'ped' => array(),
			'osb' => array(),*/
			'ap'=>'Not Defined'
		);
		foreach ($this->cubicleElevationFins as $val) {
			/* @var $val CubicleElevationFin	 */
			$fin['cef'][$val->cubicle_elevation_id] = $this->getFace($val);
		}
		
		foreach (AccessoryFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cubicle->item_id) as $val) {
			/* @var $val AccessoryFinish */
			$fin['acc'][$val->accessory_id] = ($val->finish_id ? $val->finish->name : 'acc Finish-not Defined');
		}
		foreach (OverHeadStorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cubicle->item_id) as $val) {
			/* @var $val OverHeadStorageFinish */
			if ($val->finish_id) {
				$fin['ohd'][$val->over_head_storage_id] = $val->finish->name;
				$fin['osb'][$val->over_head_storage_id] = $val->bracketFinish->name;
			}
			else
				$fin['ohd'][$val->over_head_storage_id] = $fin['osb'][$val->over_head_storage_id] = 'ohd Finish-not Defined';
		}
		foreach (PedestalFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cubicle->item_id) as $val) {
			/* @var $val PedestalFinish */
			if ($val->finish_id)
				$fin['ped'][$val->pedestal_id] = ($val->finish_id ? $val->finish->name : 'ped Finish-not Defined');
		}
		foreach (StorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cubicle->item_id) as $val) {
				$fin['st'][$val->storage_id] = ($val->storage->storage_ft_id > 1 ? $val->finish->name : 'storage Finish-not Defined');
		}
		return ($fin);
	}

	/** @param CubicleElevationFin $face */
	function getFace($face) {
		if (!$face)
			return array('a2' => 0, 'a1' => 0, 'tt' => 0, 'ts' => 0, 'mt' => 0, 'ms' => 0, 'ra' => 0, 'bt' => 0, 'rb' => 0, 'b' => 0, 'bs' => 0, 'sk' => 0,);

		return array(
			'a2' => $face->addon2_finish_id ? $face->addon2Finish->name : 0,
			'a1' => $face->addon1_finish_id ? $face->addon1Finish->name : 0,
			'tt' => $face->top_tile_finish_id ? $face->topTileFinish->name : 0,
			'ts' => $face->top_split_tile_finish_id ? $face->topSplitTileFinish->name : 0,
			'mt' => $face->mid_tile_finish_id ? $face->midTileFinish->name : 0,
			'ms' => $face->mid_split_tile_finish_id ? $face->midSplitTileFinish->name : 0,
			'ra' => $face->rwa_finish_id ? $face->rwaFinish->name : 0,
			'b' => $face->band_finish_id ? $face->bandFinish->name : 0,
			'rb' => $face->rwb_finish_id ? $face->rwbFinish->name : 0,
			'bt' => $face->bot_finish_id ? $face->botFinish->name : 0,
			'bs' => $face->bot_split_finish_id ? $face->botSplitFinish->name : 0,
			'sk' => $face->sk_finish_id ? $face->skFinish->name : 0,
		);
	}
	
	public function getFinishID() {
		$fin = array(
			'cef' => array(),
			'tbl' => $this->table_top_finish_id ? $this->tableTopFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sgf' => $this->other_understructure_finish_id ? $this->otherUnderstructureFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sup' => array(
				'f' => $this->front_understructure_finish_id ? $this->frontUnderstructureFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				's' => $this->side_understructure_finish_id ? $this->sideUnderstructureFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'b' => $this->other_understructure_finish_id ? $this->otherUnderstructureFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0'),
			'pm' => array(
				'f' => $this->front_modesty_finish_id ? $this->frontModestyFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				's' => $this->side_modesty_finish_id ? $this->sideModestyFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'b' => $this->back_modesty_finish_id ? $this->backModestyFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0'),
			'ca' => array(
				'f' => $this->front_cable_access_finish_id ? $this->frontCableAccessFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				's' => $this->side_cable_access_finish_id ? $this->sideCableAccessFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'b' => $this->back_cable_access_finish_id ? $this->backCableAccessFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			),
			'ch' => array(
				'f' => $this->front_cable_carrier_finish_id ? $this->frontCableCarrierFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				's' => $this->side_cable_carrier_finish_id ? $this->sideCableCarrierFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'b' => $this->back_cable_carrier_finish_id ? $this->backCableCarrierFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',),
			'ce' => array(
				'f' => $this->front_cable_entry_finish_id ? $this->frontCableEntryFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				's' => $this->side_cable_entry_finish_id ? $this->sideCableEntryFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'b' => $this->back_cable_entry_finish_id ? $this->backCableEntryFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',),
			'st' => array(
				's' => $this->side_storage_finish_id ? $this->sideStorageFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'b' => $this->back_storage_finish_id ? $this->backStorageFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0'
			),
			'sf' => $this->frame_pc_finish_id ? $this->framePcFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                        'per_leg' => array(
                            'side' => $this->perform_leg_finish ? $this->performLegFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                            'cen' => $this->other_understructure_finish_id ? $this->otherUnderstructureFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                            ),
			/*'acc' => array(),
			'ohd' => array(),
			'ped' => array(),
			'osb' => array(),*/
			'ap'=>'0-0-0-0-0-0-0-0-0-0-0-0-0'
		);
		foreach ($this->cubicleElevationFins as $val) {
			/* @var $val CubicleElevationFin	 */
			$fin['cef'][$val->cubicle_elevation_id] = $this->getFaceID($val);
		}
		
		foreach (AccessoryFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cubicle->item_id) as $val) {
			/* @var $val AccessoryFinish */
			$fin['acc'][$val->accessory_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		foreach (OverHeadStorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cubicle->item_id) as $val) {
			/* @var $val OverHeadStorageFinish */
			if ($val->finish_id) {
				$fin['ohd'][$val->over_head_storage_id] = $val->finish->getFinID();
				$fin['osb'][$val->over_head_storage_id] = $val->bracketFinish->getFinID();
			} else
				$fin['ohd'][$val->over_head_storage_id] = $fin['osb'][$val->over_head_storage_id] = '0-0-0-0-0-0-0-0-0-0-0-0-0';
		}
		foreach (PedestalFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cubicle->item_id) as $val) {
			/* @var $val PedestalFinish */
			$fin['ped'][$val->pedestal_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		foreach (StorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cubicle->item_id) as $val) {
			$fin['st'][$val->storage_id] = ($val->storage->storage_ft_id > 1 ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		return ($fin);
	}

	/** @param CubicleElevationFin $face */
	function getFaceID($face) {
		if (!$face)
			return array(
				'a2' => '0-0-0-0-0-0-0-0-0-0-0-0-0', 
				'a1' => '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'tt' => '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'ts' => '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'mt' => '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'ms' => '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'ra' => '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'bt' => '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'rb' => '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'b' => '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'bs' => '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'sk' => '0-0-0-0-0-0-0-0-0-0-0-0-0',);

		return array(
			'a2' => $face->addon2_finish_id ? $face->addon2Finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'a1' => $face->addon1_finish_id ? $face->addon1Finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'tt' => $face->top_tile_finish_id ? $face->topTileFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'ts' => $face->top_split_tile_finish_id ? $face->topSplitTileFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'mt' => $face->mid_tile_finish_id ? $face->midTileFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'ms' => $face->mid_split_tile_finish_id ? $face->midSplitTileFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'ra' => $face->rwa_finish_id ? $face->rwaFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'b' => $face->band_finish_id ? $face->bandFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'rb' => $face->rwb_finish_id ? $face->rwbFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'bt' => $face->bot_finish_id ? $face->botFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'bs' => $face->bot_split_finish_id ? $face->botSplitFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sk' => $face->sk_finish_id ? $face->skFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
		);
	}

}

?>