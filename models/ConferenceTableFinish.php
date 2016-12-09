<?php

Yii::import('application.models._base.BaseConferenceTableFinish');

class ConferenceTableFinish extends BaseConferenceTableFinish {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'conference_table_id' => 'Conference Table',
			'table_top_finish_id' => 'Table Top Finish',
			'support_finish_id' => 'Support Finish',
			'cable_access_finish_id' => 'Cable Access Finish',
			'cable_entry_finish_id' => 'Cable Entry Finish',
			'cable_tray_finish_id' => 'Cable Tray Finish',
			'modesty_finish_id' => 'Modesty Finish',
                        'perform_leg_finish' => 'Desking Leg Finish',
			'data' => 'Data',
		);
	}

	public function beforeDelete() {
		StorageFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->conferenceTable->item_id);
		AccessoryFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->conferenceTable->item_id);
		OverHeadStorage::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->conferenceTable->item_id);
		PedestalFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->conferenceTable->item_id);
		return (parent::beforeDelete());
	}

	public function getFinish() {
		$fin = array(
			'tbl' => $this->table_top_finish_id ? $this->tableTopFinish->name : '???',
			'sup' => $this->support_finish_id ? $this->supportFinish->name : '???',
			'sgf' => $this->support_finish_id ? $this->supportFinish->name : '???',
			'ca' => $this->cable_access_finish_id ? $this->cableAccessFinish->name : '???',
			'ce' => $this->cable_entry_finish_id ? $this->cableEntryFinish->name : '???',
			'ch' => $this->cable_tray_finish_id ? $this->cableTrayFinish->name : '???',
			'sf' => $this->cable_tray_finish_id ? $this->cableTrayFinish->name : '???',
			'pm' => $this->modesty_finish_id ? $this->modestyFinish->name : '???',
                        'per_leg' => array(
                            'side' => $this->perform_leg_finish ? $this->performLegFinish->name : '???',
                            'cen' => $this->perform_leg_finish ? $this->performLegFinish->name : '???',
                            ),
				/* 'acc' => array(),
				  'ohd' => array(),
				  'st' => array(),
				  'ped' => array(),
				  'osb' => array(), */
		);

		foreach (AccessoryFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->conferenceTable->item_id) as $val) {
			/* @var $val AccessoryFinish */
			$fin['acc'][$val->accessory_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		foreach (OverHeadStorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->conferenceTable->item_id) as $val) {
			/* @var $val OverHeadStorageFinish */
			if ($val->finish_id) {
				$fin['ohd'][$val->over_head_storage_id] = $val->finish->getFinID();
				$fin['osb'][$val->over_head_storage_id] = $val->bracketFinish->getFinID();
			} else
				$fin['ohd'][$val->over_head_storage_id] = $fin['osb'][$val->over_head_storage_id] = '0-0-0-0-0-0-0-0-0-0-0-0-0';
		}
		foreach (PedestalFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->conferenceTable->item_id) as $val) {
			/* @var $val PedestalFinish */
			$fin['ped'][$val->pedestal_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		foreach (StorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->conferenceTable->item_id) as $val) {
			$fin['st'][$val->storage_id] = ($val->storage->storage_ft_id > 1 ? $val->finish > getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		return ($fin);
	}

	public function getFinishID() {
		$fin = array(
			'tbl' => $this->table_top_finish_id ? $this->tableTopFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sup' => $this->support_finish_id ? $this->supportFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sgf' => $this->support_finish_id ? $this->supportFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'ca' => $this->cable_access_finish_id ? $this->cableAccessFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'ce' => $this->cable_entry_finish_id ? $this->cableEntryFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'ch' => $this->cable_tray_finish_id ? $this->cableTrayFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'sf' => $this->cable_tray_finish_id ? $this->cableTrayFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
			'pm' => $this->modesty_finish_id ? $this->modestyFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                        'per_leg' => array(
                            'side' => $this->perform_leg_finish ? $this->performLegFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                            'cen' => $this->support_finish_id ? $this->supportFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                            ),
				/* 'acc' => array(),
				  'ohd' => array(),
				  'st' => array(),
				  'ped' => array(),
				  'osb' => array(), */
		);

		foreach (AccessoryFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->conferenceTable->item_id) as $val) {
			/* @var $val AccessoryFinish */
			$fin['acc'][$val->accessory_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		foreach (OverHeadStorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->conferenceTable->item_id) as $val) {
			/* @var $val OverHeadStorageFinish */
			if ($val->finish_id) {
				$fin['ohd'][$val->over_head_storage_id] = $val->finish->getFinID();
				$fin['osb'][$val->over_head_storage_id] = $val->bracketFinish->getFinID();
			} else
				$fin['ohd'][$val->over_head_storage_id] = $fin['osb'][$val->over_head_storage_id] = '0-0-0-0-0-0-0-0-0-0-0-0-0';
		}
		foreach (PedestalFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->conferenceTable->item_id) as $val) {
			/* @var $val PedestalFinish */
			$fin['ped'][$val->pedestal_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		foreach (StorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->conferenceTable->item_id) as $val) {
			$fin['st'][$val->storage_id] = ($val->storage->storage_ft_id > 1 ? $val->finish > getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
		}
		return ($fin);
	}

}

?>