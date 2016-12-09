<?php

Yii::import('application.models._base.BaseCabinTableFinish');

class CabinTableFinish extends BaseCabinTableFinish {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'cabin_table_id' => 'Cabin Table',
			'table_top_finish_id' => 'Table Top Finish',
			'front_support_finish_id' => 'Front Support Finish',
			'side_support_finish_id' => 'Side Support Finish',
			'other_understructure_pc_finish_id' => 'Other Understructure Pc Finish',
			'front_modesty_finish_id' => 'Front Modesty Finish',
			'side_modesty_finish_id' => 'Side Modesty Finish',
			'back_modesty_finish_id' => 'Back Modesty Finish',
			'front_cable_access_finish_id' => 'Front Cable Access Finish',
			'front_cable_carrier_finish_id' => 'Front Cable Carrier Finish',
			'front_cable_entry_finish_id' => 'Front Cable Entry Finish',
			'side_cable_access_finish_id' => 'Side Cable Access Finish',
			'side_cable_holder_finish_id' => 'Side Cable Holder Finish',
			'side_cable_entry_finish_id' => 'Side Cable Entry Finish',
			'side_storage_finish_id' => 'Side Storage Finish',
			'back_storage_finish_id' => 'Back Storage Finish',
                        'perform_leg_finish' => 'Desking Leg Finish',
			'data' => 'Data',
		);
	}

	public function beforeDelete() {
		StorageFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->cabinTable->item_id);
		AccessoryFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->cabinTable->item_id);
		OverHeadStorage::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->cabinTable->item_id);
		PedestalFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->cabinTable->item_id);
		return (parent::beforeDelete());
	}

	public function getFinish() {
		$fin = array(
			'tbl' => $this->table_top_finish_id ? $this->tableTopFinish->name : '???',
			'sgf' => $this->other_understructure_pc_finish_id ? $this->otherUnderstructurePcFinish->name : '???',
			'sf' => $this->other_understructure_pc_finish_id ? $this->otherUnderstructurePcFinish->name : '???',
                        'sup1' => $this->other_understructure_pc_finish_id ? $this->otherUnderstructurePcFinish->name : '???',
			'ap' => $this->other_understructure_pc_finish_id ? $this->otherUnderstructurePcFinish->name : '???',
			'sup' => array(
				'f' => $this->front_support_finish_id ? $this->frontSupportFinish->name : '???',
				's' => $this->side_support_finish_id ? $this->sideSupportFinish->name : '???',
				'b' => $this->other_understructure_pc_finish_id ? $this->otherUnderstructurePcFinish->name : '???'),
			'pm' => array(
				'f' => $this->front_modesty_finish_id ? $this->frontModestyFinish->name : '???',
				's' => $this->side_modesty_finish_id ? $this->sideModestyFinish->name : '???',
				'b' => $this->back_modesty_finish_id ? $this->backModestyFinish->name : '???'),
			'ca' => array(
				'f' => $this->front_cable_access_finish_id ? $this->frontCableAccessFinish->name : '???',
				's' => $this->side_cable_access_finish_id ? $this->sideCableAccessFinish->name : '???',),
			'ch' => array(
				'f' => $this->front_cable_carrier_finish_id ? $this->frontCableCarrierFinish->name : '???',
				's' => $this->side_cable_holder_finish_id ? $this->sideCableHolderFinish->name : '???',),
			'ce' => array(
				'f' => $this->front_cable_entry_finish_id ? $this->frontCableEntryFinish->name : '???',
				's' => $this->side_cable_entry_finish_id ? $this->sideCableEntryFinish->name : '???',),
			'st' => array(
				's' => $this->side_storage_finish_id ? $this->sideStorageFinish->name : '???',
				'b' => $this->back_storage_finish_id ? $this->backStorageFinish->name : '???',),
                        'per_leg' => array(
                            'side' => $this->perform_leg_finish ? $this->performLegFinish->name : '???',
                            'cen' => $this->perform_leg_finish ? $this->performLegFinish->name : '???',
                            ),
				/* 'acc' => array(),
				  'ohd' => array(),
				  'ped' => array(),
				  'osb' => array(), */
		);
		foreach (AccessoryFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cabinTable->item_id) as $val) {
			/* @var $val AccessoryFinish */
			$fin['acc'][$val->accessory_id] = ($val->finish_id ? $val->finish->name : 'acc Finish-not Defined');
		}
		foreach (OverHeadStorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cabinTable->item_id) as $val) {
			/* @var $val OverHeadStorageFinish */
			if ($val->finish_id) {
				$fin['ohd'][$val->over_head_storage_id] = $val->finish->name;
				$fin['osb'][$val->over_head_storage_id] = $val->bracketFinish->name;
			} else
				$fin['ohd'][$val->over_head_storage_id] = $fin['osb'][$val->over_head_storage_id] = 'ohd Finish-not Defined';
		}
		foreach (PedestalFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cabinTable->item_id) as $val) {
			/* @var $val PedestalFinish */
			if ($val->finish_id)
				$fin['ped'][$val->pedestal_id] = ($val->finish_id ? $val->finish->name : 'ped Finish-not Defined');
		}
		foreach (StorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cabinTable->item_id) as $val) {
			$fin['st'][$val->storage_id] = ($val->storage->storage_ft_id > 1 ? $val->finish->name : 'storage Finish-not Defined');
		}
		return ($fin);
	}

	public function getFinishID() {
		$fin = array(
			'tbl' => $this->table_top_finish_id ? $this->tableTopFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sgf' => $this->other_understructure_pc_finish_id ? $this->otherUnderstructurePcFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sf' => $this->other_understructure_pc_finish_id ? $this->otherUnderstructurePcFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                        'sup1' => $this->other_understructure_pc_finish_id ? $this->otherUnderstructurePcFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'ap' => $this->other_understructure_pc_finish_id ? $this->otherUnderstructurePcFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sup' => array(
				'f' => $this->front_support_finish_id ? $this->frontSupportFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				's' => $this->side_support_finish_id ? $this->sideSupportFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'b' => $this->other_understructure_pc_finish_id ? $this->otherUnderstructurePcFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0'),
			'pm' => array(
				'f' => $this->front_modesty_finish_id ? $this->frontModestyFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				's' => $this->side_modesty_finish_id ? $this->sideModestyFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'b' => $this->back_modesty_finish_id ? $this->backModestyFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0'),
			'ca' => array(
				'f' => $this->front_cable_access_finish_id ? $this->frontCableAccessFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				's' => $this->side_cable_access_finish_id ? $this->sideCableAccessFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0'),
			'ch' => array(
				'f' => $this->front_cable_carrier_finish_id ? $this->frontCableCarrierFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				's' => $this->side_cable_holder_finish_id ? $this->sideCableHolderFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0'),
			'ce' => array(
				'f' => $this->front_cable_entry_finish_id ? $this->frontCableEntryFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				's' => $this->side_cable_entry_finish_id ? $this->sideCableEntryFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0'),
			'st' => array(
				's' => $this->side_storage_finish_id ? $this->sideStorageFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
				'b' => $this->back_storage_finish_id ? $this->backStorageFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0'),
                        'per_leg' => array(
                            'side' => $this->perform_leg_finish ? $this->performLegFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                            'cen' => $this->other_understructure_pc_finish_id ? $this->otherUnderstructurePcFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                            ),
				/* 'acc' => array(),
				  'ohd' => array(),
				  'ped' => array(),
				  'osb' => array(), */
		);
		foreach (AccessoryFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cabinTable->item_id) as $val) {
			/* @var $val AccessoryFinish */
			$fin['acc'][$val->accessory_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		foreach (OverHeadStorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cabinTable->item_id) as $val) {
			/* @var $val OverHeadStorageFinish */
			if ($val->finish_id) {
				$fin['ohd'][$val->over_head_storage_id] = $val->finish->getFinID();
				$fin['osb'][$val->over_head_storage_id] = $val->bracketFinish->getFinID();
			} else
				$fin['ohd'][$val->over_head_storage_id] = $fin['osb'][$val->over_head_storage_id] = '0-0-0-0-0-0-0-0-0-0-0-0-0';
		}
		foreach (PedestalFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cabinTable->item_id) as $val) {
			/* @var $val PedestalFinish */
			$fin['ped'][$val->pedestal_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		foreach (StorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->cabinTable->item_id) as $val) {
			$fin['st'][$val->storage_id] = ($val->storage->storage_ft_id > 1 ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		return ($fin);
	}

}

?>