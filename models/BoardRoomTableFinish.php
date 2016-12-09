<?php

Yii::import('application.models._base.BaseBoardRoomTableFinish');

class BoardRoomTableFinish extends BaseBoardRoomTableFinish {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'board_room_table_id' => 'Board Room Table',
			'table_top_finish_id' => 'Table Top Finish',
			'under_structure_finish_id' => 'Under Structure Finish',
			'cable_access_finish_id' => 'Cable Access Finish',
			'cable_carrier_finish_id' => 'Cable Carrier Finish',
			'cable_entry_finish_id' => 'Cable Entry Finish',
			'modesty_finish_id' => 'Modesty Finish',
                        'perform_leg_finish' => 'Desking Leg Finish',
			'data' => 'Data',
		);
	}
	public function beforeDelete() {
		StorageFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id);
		AccessoryFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id);
		OverHeadStorage::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id);
		PedestalFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id);
		return (parent::beforeDelete());
	}

	public function getFinish() {
		$fin = array(
			'tbl' => $this->table_top_finish_id ? $this->tableTopFinish->name : '???',
			'sup' => $this->under_structure_finish_id ? $this->underStructureFinish->name : '???',
			'sgf' => $this->under_structure_finish_id ? $this->underStructureFinish->name : '???',
			'ca' => $this->cable_access_finish_id ? $this->cableAccessFinish->name : '???',
			'ch' => $this->cable_carrier_finish_id ? $this->cableCarrierFinish->name : '???',
			'sf' => $this->cable_carrier_finish_id ? $this->cableCarrierFinish->name : '???',
			'ce' => $this->cable_entry_finish_id ? $this->cableEntryFinish->name : '???',
			'pm' => $this->modesty_finish_id ? $this->modestyFinish->name : '???',
                        'per_leg' => array(
                            'side' => $this->perform_leg_finish ? $this->performLegFinish->name : '???',
                            'cen' => $this->meeting_support_finish_id ? $this->meetingSupportFinish->name : '???',
                            ),
			/*'acc' => array(),
			'ohd' => array(),
			'st' => array(),
			'ped' => array(),
			'osb' => array(),*/
		);
		foreach (AccessoryFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id) as $val) {
			/* @var $val AccessoryFinish */
			$fin['acc'][$val->accessory_id] = ($val->finish_id ? $val->finish->name : 'acc Finish-not Defined');
		}
		foreach (OverHeadStorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id) as $val) {
			/* @var $val OverHeadStorageFinish */
			if ($val->finish_id) {
				$fin['ohd'][$val->over_head_storage_id] = $val->finish->name;
				$fin['osb'][$val->over_head_storage_id] = $val->bracketFinish->name;
			}
			else
				$fin['ohd'][$val->over_head_storage_id] = $fin['osb'][$val->over_head_storage_id] = 'ohd Finish-not Defined';
		}
		foreach (PedestalFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id) as $val) {
			/* @var $val PedestalFinish */
			if ($val->finish_id)
				$fin['ped'][$val->pedestal_id] = ($val->finish_id ? $val->finish->name : 'ped Finish-not Defined');
		}
		foreach (StorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id) as $val) {
				$fin['st'][$val->storage_id] = ($val->storage->storage_ft_id > 1 ? $val->finish->name : 'storage Finish-not Defined');
		}
		return ($fin);
	}
	
	public function getFinishID() {
		$fin = array(
			'tbl' => $this->table_top_finish_id ? $this->tableTopFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sup' => $this->under_structure_finish_id ? $this->underStructureFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sgf' => $this->under_structure_finish_id ? $this->underStructureFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'ca' => $this->cable_access_finish_id ? $this->cableAccessFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'ch' => $this->cable_carrier_finish_id ? $this->cableCarrierFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sf' => $this->cable_carrier_finish_id ? $this->cableCarrierFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'ce' => $this->cable_entry_finish_id ? $this->cableEntryFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'pm' => $this->modesty_finish_id ? $this->modestyFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                        'per_leg' => array(
                            'side' => $this->perform_leg_finish ? $this->performLegFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                            'cen' => $this->under_structure_finish_id ? $this->underStructureFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                            ),
			/*'acc' => array(),
			'ohd' => array(),
			'st' => array(),
			'ped' => array(),
			'osb' => array(),*/
		);
		foreach (AccessoryFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id) as $val) {
			/* @var $val AccessoryFinish */
			$fin['acc'][$val->accessory_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		foreach (OverHeadStorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id) as $val) {
			/* @var $val OverHeadStorageFinish */
			if ($val->finish_id) {
				$fin['ohd'][$val->over_head_storage_id] = $val->finish->getFinID();
				$fin['osb'][$val->over_head_storage_id] = $val->bracketFinish->getFinID();
			}
			else
				$fin['ohd'][$val->over_head_storage_id] = $fin['osb'][$val->over_head_storage_id] = '0-0-0-0-0-0-0-0-0-0-0-0-0';
		}
		foreach (PedestalFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id) as $val) {
			/* @var $val PedestalFinish */
			$fin['ped'][$val->pedestal_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		foreach (StorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->boardRoomTable->item_id) as $val) {
			$fin['st'][$val->storage_id] = ($val->storage->storage_ft_id > 1 ? $val->finish > getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		return ($fin);
	}

}

?>