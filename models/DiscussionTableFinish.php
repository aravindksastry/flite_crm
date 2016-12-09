<?php

Yii::import('application.models._base.BaseDiscussionTableFinish');

class DiscussionTableFinish extends BaseDiscussionTableFinish {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'discussion_table_id' => 'Discussion Table',
            'table_top__finish_id' => 'Table Top Finish',
            'discussion_support_finish1_id' => 'Discussion Support Finish1',
            'discussion_support_finish2_id' => 'Discussion Support Finish2',
            'cable_access_finish_id' => 'Cable Access Finish',
            'cable_entry_finish_id' => 'Cable Entry Finish',
            'perform_leg_finish' => 'Desking Leg Finish',
            'data' => 'Data',
        );
    }

    public function beforeDelete() {
        StorageFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->discussionTable->item_id);
        AccessoryFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->discussionTable->item_id);
        OverHeadStorage::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->discussionTable->item_id);
        PedestalFinish::model()->deleteAll('design_finish_id =' . $this->id . ' and item_id=' . $this->discussionTable->item_id);
        return (parent::beforeDelete());
    }

    public function getFinish() {
        $fin = array(
            'tbl' => $this->table_top__finish_id ? $this->tableTopFinish->name : '???',
            'ca' => $this->cable_access_finish_id ? $this->cableAccessFinish->name : '???',
            'ce' => $this->cable_entry_finish_id ? $this->cableEntryFinish->name : '???',
            'sup' => $this->discussion_support_finish1_id ? $this->discussionSupportFinish1->name : '???',
            'sf' => $this->discussion_support_finish2_id ? $this->discussionSupportFinish2->name : '???',
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
        
        foreach (AccessoryFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->discussionTable->item_id) as $val) {
            /* @var $val AccessoryFinish */
            $fin['acc'][$val->accessory_id] = ($val->finish_id ? $val->finish->name : 'acc Finish-not Defined');
        }
        foreach (OverHeadStorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->discussionTable->item_id) as $val) {
            /* @var $val OverHeadStorageFinish */
            if ($val->finish_id) {
                $fin['ohd'][$val->over_head_storage_id] = $val->finish->name;
                $fin['osb'][$val->over_head_storage_id] = $val->bracketFinish->name;
            }
            else
                $fin['ohd'][$val->over_head_storage_id] = $fin['osb'][$val->over_head_storage_id] = 'ohd Finish-not Defined';
        }
        foreach (PedestalFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->discussionTable->item_id) as $val) {
            /* @var $val PedestalFinish */
            if ($val->finish_id)
                $fin['ped'][$val->pedestal_id] = ($val->finish_id ? $val->finish->name : 'ped Finish-not Defined');
        }
        foreach (StorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->discussionTable->item_id) as $val) {
            $fin['st'][$val->storage_id] = ($val->storage->storage_ft_id > 1 ? $val->finish->name : 'storage Finish-not Defined');
        }
        return ($fin);
    }
	
	public function getFinishID() {
        $fin = array(
            'tbl' => $this->table_top__finish_id ? $this->tableTopFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
            'ca' => $this->cable_access_finish_id ? $this->cableAccessFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
            'ce' => $this->cable_entry_finish_id ? $this->cableEntryFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
            'sup' => $this->discussion_support_finish1_id ? $this->discussionSupportFinish1->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
            'sf' => $this->discussion_support_finish2_id ? $this->discussionSupportFinish2->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
            'per_leg' => array(
                    'side' => $this->perform_leg_finish ? $this->performLegFinish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                    'cen' => $this->discussion_support_finish1_id ? $this->discussionSupportFinish1->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0',
                    ),
                /* 'acc' => array(),
                  'ohd' => array(),
                  'st' => array(),
                  'ped' => array(),
                  'osb' => array(), */
        );
        
        foreach (AccessoryFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->discussionTable->item_id) as $val) {
            /* @var $val AccessoryFinish */
            $fin['acc'][$val->accessory_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
        }
        foreach (OverHeadStorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->discussionTable->item_id) as $val) {
            /* @var $val OverHeadStorageFinish */
            if ($val->finish_id) {
				$fin['ohd'][$val->over_head_storage_id] = $val->finish->getFinID();
				$fin['osb'][$val->over_head_storage_id] = $val->bracketFinish->getFinID();
			} else
				$fin['ohd'][$val->over_head_storage_id] = $fin['osb'][$val->over_head_storage_id] = '0-0-0-0-0-0-0-0-0-0-0-0-0';
        }
        foreach (PedestalFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->discussionTable->item_id) as $val) {
            /* @var $val PedestalFinish */
            $fin['ped'][$val->pedestal_id] = ($val->finish_id ? $val->finish->getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
        }
        foreach (StorageFinish::model()->findAll('design_finish_id=' . $this->id . ' and item_id=' . $this->discussionTable->item_id) as $val) {
            $fin['st'][$val->storage_id] = ($val->storage->storage_ft_id > 1 ? $val->finish > getFinID() : '0-0-0-0-0-0-0-0-0-0-0-0-0');
        }
        return ($fin);
    }

}

?>