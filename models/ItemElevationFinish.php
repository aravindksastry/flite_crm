<?php

Yii::import('application.models._base.BaseItemElevationFinish');

class ItemElevationFinish extends BaseItemElevationFinish {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'item_id' => 'Item',
			'addon2_finish_id' => 'Addon2 Finish',
			'addon1_finish_id' => 'Addon1 Finish',
			'top_finish_id' => 'Top Finish',
			'top_split_finish_id' => 'Top Split Finish',
			'mid_finish_id' => 'Mid Finish',
			'mid_split_finish_id' => 'Mid Split Finish',
			'rwa_finish_id' => 'Rwa Finish',
			'band_finish_id' => 'Band Finish',
			'rwb_finish_id' => 'Rwb Finish',
			'bot_finish_id' => 'Bot Finish',
			'bot_split_finish_id' => 'Bot Split Finish',
			'sk_finish_id' => 'Sk Finish',
			'data' => 'Data',
		);
	}

	public function beforeSave() {
		$this->data = $this->addon2Finish->finish_type_id . ':' .
				$this->addon1Finish->finish_type_id . ':' .
				$this->topFinish->finish_type_id . ':' .
				$this->topSplitFinish->finish_type_id . ':' .
				$this->midFinish->finish_type_id . ':' .
				$this->midSplitFinish->finish_type_id . ':' .
				$this->botFinish->finish_type_id . ':' .
				$this->botSplitFinish->finish_type_id;
		return parent::beforeSave();
	}

	public function afterSave() {
		$wfid = array();
		$wsmf = array(
			$this->wsMrieFinishes,
			$this->wsMrieFinishes1,
			$this->wsMrieFinishes2,
			$this->wsMrieFinishes3,
			$this->wsMrieFinishes4,
			$this->wsMrieFinishes5,
			$this->wsMrieFinishes6,
			$this->wsMrieFinishes7);
		foreach ($wsmf as $val) {
			foreach ($val as $wsm) {
				/* @var $wsm wsMrieFinish */
				if (!in_array($wsm->workstation_finish_id, $wfid))
					$wfid[] = $wsm->workstation_finish_id;
			}
		}
		$wf120id = array();
		$wsmf120 = array(
			$this->wsMrie120Finishes,
			$this->wsMrie120Finishes1,
			$this->wsMrie120Finishes2,
			$this->wsMrie120Finishes3,
			$this->wsMrie120Finishes4,
			$this->wsMrie120Finishes5,
			$this->wsMrie120Finishes6,
			$this->wsMrie120Finishes7);
		foreach ($wsmf120 as $val) {
			foreach ($val as $wsm) {
				/* @var $wsm wsMrie120Finish */
				if (!in_array($wsm->workstation_120_finish_id, $wf120id))
					$wf120id[] = $wsm->workstation_120_finish_id;
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