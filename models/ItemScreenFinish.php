<?php

Yii::import('application.models._base.BaseItemScreenFinish');

class ItemScreenFinish extends BaseItemScreenFinish {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'item_id' => 'Item',
			'ss_finish_id' => 'Ss Finish',
			'mid_face1_finish_id' => 'Mid Face1 Finish',
			'mid_face2_finish_id' => 'Mid Face2 Finish',
			'mid_split_face1_finish_id' => 'Mid Split Face1 Finish',
			'mid_split_face2_finish_id' => 'Mid Split Face2 Finish',
			'bot_face1_finish_id' => 'Bot Face1 Finish',
			'bot_face2_finish_id' => 'Bot Face2 Finish',
			'data' => 'Data',
		);
	}
	
	public function afterSave() {
		$wfid = array();
		$wsmf = array(
			$this->wsMrieFinishes,
			$this->wsMrieFinishes1,
			$this->wsMrieFinishes2,
			$this->wsMrieFinishes3);
		foreach ($wsmf as $val) {
			foreach ($val as $wsm) {
				/* @var $wsm wsMrieFinish */
				if (!in_array($wsm->workstation_finish_id, $wfid))
				{
					$wfid[] = $wsm->workstation_finish_id;
				}
			}
		}
		$wf120id = array();
		$wsmf120 = array(
			$this->wsMrie120Finishes,
			$this->wsMrie120Finishes1,
			$this->wsMrie120Finishes2,
			$this->wsMrie120Finishes3);
		foreach ($wsmf120 as $val) {
			foreach ($val as $wsm) {
				/* @var $wsm wsMrie120Finish */
				if (!in_array($wsm->workstation_120_finish_id, $wf120id))
				{
					$wf120id[] = $wsm->workstation_120_finish_id;
				}
			}
		}
		foreach ($wfid as $val) {
			$model = WorkstationFinish::model()->findByPk($val);
			$model->save();
		}
		foreach ($wf120id as $val) {
			$model = Workstation120Finish::model()->findByPk($val);
			$model->save();
		}
		return (parent::afterSave());
	}

}

?>