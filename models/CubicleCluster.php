<?php

Yii::import('application.models._base.BaseCubicleCluster');

class CubicleCluster extends BaseCubicleCluster {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'cubicle_id' => 'Cubicle',
			'units_per_cluster' => 'Units Per Cluster',
			'data' => 'Data',
		);
	}

	public function beforeSave() {
		if ($this->isNewRecord && $this->data > 1) {
			$cid = CubicleCluster::model()->find('id=' . $this->data);
			$this->name = $cid->name . ':clone';
			$this->units_per_cluster = $cid->units_per_cluster;
		}
		return (parent::beforeSave());
	}

	public function afterSave() {
		if ($this->isNewRecord && strpos($this->name, ':clone') === FALSE) {
			foreach (CubicleCoOrdinate::model()->findAll(array('order' => 't.name ASC')) as $val) {
				$cs = new CubicleSpine;
				$cs->name = $val->id;
				$cs->cubicle_cluster_id = $this->id;
				$cs->save();
			}
		}
		return (parent::afterSave());
	}

	public function beforeDelete() {
		CubicleSpine::model()->deleteAll('cubicle_cluster_id =' . $this->id);
		return (parent::beforeDelete());
	}

}

?>