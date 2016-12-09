<?php

Yii::import('application.models._base.BaseCluster');

class Cluster extends BaseCluster
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	public function attributeLabels() {
		return array(
							'id' => 'ID',
							'name' => 'Name',
							'arrangement' => 'Arrangement',
							'start_wall' => 'Start Wall',
							'end_wall' => 'End Wall',
							'n_wall' => 'N Wall',
							's_wall' => 'S Wall',
							'r_tbl' => 'R Tbl',
							'l_tbl' => 'L Tbl',
							'tiles' => 'Tiles',
							'junc' => 'Junc',
							'p_raceway' => 'P Raceway',
							'spare' => 'Spare',
				);
	}
	public function beforeSave() {
		$parms = array(
			'm' => array('s' => 0, 'lp' => 0, 'rp' => 0, 'le' => 0, 're' => 0, 'lw' => 0, 'rw' => 0, 'ld' => 0, 'rd' => 0),
			'r' => array('s' => 0, 'lp' => 0, 'rp' => 0, 'le' => 0, 're' => 0, 'lw' => 0, 'rw' => 0, 'ld' => 0, 'rd' => 0),
			'i' => array('s' => 0, 'lp' => 0, 'rp' => 0, 'le' => 0, 're' => 0, 'lw' => 0, 'rw' => 0, 'ld' => 0, 'rd' => 0),
		);

		$top = $bot = 0;
		$trimmed = rtrim($this->arrangement, '0');
		$cnt = strlen($trimmed);
		$str = ")";
		if ($this->n_wall)
			$str.='t';
		if ($this->s_wall)
			$str.='b';
		if ($this->end_wall)
			$str .= 'W-';
		$tcnt = 0;
		while ($cnt) {
			$cnt--;
			$odd = $cnt & 1 ? true : false;
			$top <<= 1;
			$bot <<= 1;
			$val = $trimmed[$cnt];
			if ($val == '3') {
				$parms['m']['s']++;
				$top |= 1;
				$bot |= 1;
				$str .='B';
			} elseif ($val == '1') {
				$tcnt |= 1;
				$parms['m'][$odd ? 'le' : 're']++;
				$str .= ($odd ? 'L' : 'R');
				$top |= 1;
			} elseif ($val == '2') {
				$tcnt |= 2;
				$parms['m'][$odd ? 're' : 'le']++;
				$str .= ($odd ? 'R' : 'L');
				$bot |= 1;
			} else {
				$tcnt |= 4;
				$str .='0';
			}
		}
		$this->name = $parms['m']['le'] + $parms['m']['re'] + $parms['m']['s'] * 2;
		if ($tcnt > 3)
			$this->name .= ' D ';
		elseif ($parms['m']['le'] + $parms['m']['re'])
			$this->name .= ($parms['m']['s'] ? ' Z ' : ' N ');
		elseif ($parms['m']['s'])
			$this->name .= ' S ';
		if ($parms['m']['re'] > $parms['m']['le'])
			$this->name.= ' R';
		elseif ($parms['m']['le'] > $parms['m']['re'])
			$this->name.= ' L';

		$this->name .= ' (' . ($this->start_wall ? 'W-' : '');

		$cnt = strlen($str) - 1;
		if ($str[$cnt] == '0')
			$str[$cnt] = 'A';
		else
			$str .= 'N';
		$this->name .= strrev($str);

		/*
		 * this pass scans the tables on each side with the table on the otherside providing additinal properties
		 *
		 * the problem can be viewed as 2 basic genes represented by 2 tables on the same line providing 4 properties for a table.
		 * third gene is represented by the table on the other side that adds a shared property making 8 combinational properties.
		 * The 8 properties are same for left and right table.  Left/Right variant is the 4th gene, Hence 16 combinational properties.

		 * Passage is a unique property applicable only at the ends. Hence that can be considered the 5th gene
		 * The property of the cluster is a derivitive of the above 2+1+1+1 properties.
		 *
		 * re-commented-> actually if you treat the passage as additional property to calculate the type of table interface,
		 * the logic fails to generate correct table width near the passages as per the logic provided by Arvavinda.
		 *
		 * There can be one table on the opposite side, if that table exists, it is shared on main.
		 * There can be table on the left/right side depending on odd/even location it is a left/right table
		 *
		 * 			*	R	*
		 * 				*
		 * Each star above is a possible table.  One marked 'R' is the reference table.
		 * If the bottom middle exists it is shared on main
		 * if the top left exists, depending on odd/even location it is shared on int/ret.
		 * otherway round, if the top right table exists it is shared on ret/int
		 * For starting position the one behind is assumed to be this table itself
		 * For ending location, next location assumed to be assumed this table itself.
		 * Sets the passage table seperately.
		 */

		$l = array('f' => 0, 'r' => 0, 'i' => 0, 'ri' => 0, 'm' => 0, 'mr' => 0, 'mi' => 0, 'mri' => 0);
		$r = array('f' => 0, 'r' => 0, 'i' => 0, 'ri' => 0, 'm' => 0, 'mr' => 0, 'mi' => 0, 'mri' => 0);

		$jnc = array(
			'r' => array('2l' => 0, '2r' => 0, '2lw' => 0, '2rw' => 0, '3' => 0, '3e' => 0, '3w' => 0, '3d' => 0, '4' => 0, '4s' => 0, '4n' => 0, '4d' => 0),
			'i' => array('2l' => 0, '2r' => 0, '2lw' => 0, '2rw' => 0, '3' => 0, '3e' => 0, '3w' => 0, '3d' => 0, '4' => 0, '4s' => 0, '4n' => 0, '4d' => 0),
		);

		$prw = array(
			'1' => array('50' => 0, '75' => 0, '100' => 0,),
			'2' => array('50' => 0, '75' => 0, '100' => 0,),
			'3' => array('50' => 0, '75' => 0, '100' => 0,),
		);
		$tcnt = strlen($trimmed);
		$cnt = $tcnt - 1;
		//get to last position and set the passage, junction end, perform raceway
		$odd = $cnt & 1 ? true : false;
		$val = $odd ? 'r' : 'i';
		$cur = $trimmed[$cnt];
		if ($cur == '1')
			$parms[$val][($odd ? 'l' : 'r') . ($this->end_wall ? 'w' : 'p')]++;
		elseif ($cur == '2')
			$parms[$val][($odd ? 'r' : 'l') . ($this->end_wall ? 'w' : 'p')]++;
		else {
			$parms[$val][$this->end_wall ? 'rw' : 'rp']++;
			$parms[$val][$this->end_wall ? 'lw' : 'lp']++;
		}

		//get to first position and set the passage, junction end, perform raceway
		$cnt = 0;
		while ($trimmed[$cnt] == '0')
			$cnt++;
		$odd = $cnt & 1 ? true : false;
		$val = $odd ? 'i' : 'r';
		$cur = $trimmed[$cnt];
		if ($cur == '1')
			$parms[$val][($odd ? 'l' : 'r') . ($this->start_wall ? 'w' : 'p')]++;
		elseif ($cur == '2')
			$parms[$val][($odd ? 'r' : 'l') . ($this->start_wall ? 'w' : 'p')]++;
		else {
			$parms[$val][$this->start_wall ? 'rw' : 'rp']++;
			$parms[$val][$this->start_wall ? 'lw' : 'lp']++;
		}
		$shared = $parms['m']['le'] + $parms['m']['s'];
		$parms['r']['le'] = $shared - $parms['r']['lp'] - $parms['r']['lw'];
		$parms['i']['le'] = $shared - $parms['i']['lp'] - $parms['i']['lw'];

		$shared = $parms['m']['re'] + $parms['m']['s'];
		$parms['r']['re'] = $shared - $parms['r']['rp'] - $parms['r']['rw'];
		$parms['i']['re'] = $shared - $parms['i']['rp'] - $parms['i']['rw'];

		if ($top == 0 || $bot == 0) {
			$parms['m'][($this->n_wall || $this->s_wall) ? 'lw' : 'lp'] = $parms['m']['le'];
			$parms['m'][($this->n_wall || $this->s_wall) ? 'rw' : 'rp'] = $parms['m']['re'];
			$parms['m']['le'] = $parms['m']['re'] = 0;
		}
		$prv = 1;  //if prv is set to one or is set equal to the current, as per the logic provided by featherlite,
		//the logic fails to compute the table width conrrectly. It is logical to assume that there is no table near the passage
		$cur = 1;
		$nxt = 2;
		$kv = array_keys($l);
		if ($cnt) {
			$top >>= $cnt; //get to the start position as the earlier bit-setting did not account for blanks at start
			$bot >>= $cnt;
		}
		$shared = 3; //to check if this table int/ret is shared with an adjacent table

		while ($cnt < $tcnt) {
			$val = 0;
			if (($top & $shared) == $shared)
				$val++;
			if (($bot & $shared) == $shared)
				$val++;
			if ($val) {
				$ptr = ($cnt & 1 ? 'r' : 'i');
				$parms[$ptr]['re'] -= $val;  //immaterial of the table type L/R, the interface remains same
				$parms[$ptr]['le'] -= $val;
				$parms[$ptr]['s'] += $val;  //sets the count of interface
			}
			$shared <<= 1;

			if ($cnt + 1 == $tcnt)
				$nxt = $cur;
			//check the table,
			if ($top & $cur) {
				//if this table is shared, set the pointer to shared set
				$val = ($bot & $cur ? 4 : 0);
				if ($top & $prv)
					$val |= ($cnt & 1 ? 2 : 1);
				if ($top & $nxt)
					$val |= ($cnt & 1 ? 1 : 2);
				if ($cnt & 1)
					$l[$kv[$val]]++; //set the value for the relevent table
				else
					$r[$kv[$val]]++;
			}
			if ($bot & $cur) {
				$val = ($top & $cur ? 4 : 0);
				if ($bot & $prv)
					$val |= ($cnt & 1 ? 2 : 1);
				if ($bot & $nxt)
					$val |= ($cnt & 1 ? 1 : 2);
				if ($cnt & 1)
					$r[$kv[$val]]++;
				else
					$l[$kv[$val]]++;
			}
			$prv = $cur;
			$cur = $nxt;
			$nxt <<= 1;
			$cnt++;
		}
		$jnew = array(
			'r' => array(
				'lp' => 0, 'lw' => 0, 'lnl' => 0, 'lnr' => 0, 'lnb' => 0, 'll' => 0, 'lr' => 0, 'lb' => 0,
				//2lp		2lw			3lnl		4lnr		4lnb		4ll			3lr			4lb
				'rp' => 0, 'rw' => 0, 'rnl' => 0, 'rnr' => 0, 'rnb' => 0, 'rl' => 0, 'rr' => 0, 'rb' => 0,
				//2rp		2rw			4rnl		3rnr		4rnb		3rl			3rr			4rb
				'bp' => 0, 'bw' => 0, 'bnl' => 0, 'bnr' => 0, 'bnb' => 0, 'bl' => 0, 'br' => 0, 'bb' => 0,
			//3bp		3bw			4bnl		4bnr		4bnb		4bl			4br			4bb
			),
			'i' => array(
				'lp' => 0, 'lw' => 0, 'lnl' => 0, 'lnr' => 0, 'lnb' => 0, 'll' => 0, 'lr' => 0, 'lb' => 0,
				'rp' => 0, 'rw' => 0, 'rnl' => 0, 'rnr' => 0, 'rnb' => 0, 'rl' => 0, 'rr' => 0, 'rb' => 0,
				'bp' => 0, 'bw' => 0, 'bnl' => 0, 'bnr' => 0, 'bnb' => 0, 'bl' => 0, 'br' => 0, 'bb' => 0,
			),
		);

		$cnt = strlen($trimmed) - 1;
		$odd = ($cnt & 1 ? true : false);
		$prv = $trimmed[$cnt];
		$prv = ($prv == '3' ? 'b' : ($prv == '1' ? ($odd ? 'l' : 'r') : ($odd ? 'r' : 'l')));
		$jnew[$odd ? 'r' : 'i'][$prv . ($this->end_wall ? 'w' : 'p')]++;

		$cnt = 0;
		while ($trimmed[$cnt] == '0')
			$cnt++;
		$odd = ($cnt & 1 ? true : false);
		$prv = $trimmed[$cnt];
		$prv = ($prv == '3' ? 'b' : ($prv == '1' ? ($odd ? 'l' : 'r') : ($odd ? 'r' : 'l')));
		$jnew[$odd ? 'i' : 'r'][$prv . ($this->start_wall ? 'w' : 'p')]++;
		$cnt++;
		$tcnt = strlen($trimmed);
		if ($cnt < $tcnt) {
			$prw[$prv === 'b' ? '3' : ($prv === 'l' ? '1' : '2')]['75']++;
			while ($cnt < $tcnt) {
				$odd = ($cnt & 1 ? true : false);
				$cur = $trimmed[$cnt];
				$cur = ($cur == '3' ? 'b' : ($cur == '1' ? ($odd ? 'l' : 'r') : ($cur == '2' ? ($odd ? 'r' : 'l') : 'n')));
				if ($cur == 'n') {
					$cnt++;
					$odd = ($cnt & 1 ? true : false);
					$cur = $trimmed[$cnt];
					$cur = ($cur == '3' ? 'b' : ($cur == '1' ? ($odd ? 'l' : 'r') : ($cur == '2' ? ($odd ? 'r' : 'l') : 'n')));
					$prv .= 'n';
				}
				$jnew[$odd ? 'i' : 'r'][$prv . $cur]++;
				$nloc = ($cnt + 1 < $tcnt ? true : false);
				if ($cur != 'n')
					$prw[($cur === 'b' ? '3' : ($cur === 'l' ? '1' : '2'))][($nloc ? '50' : '75')]++;
				$prv = $cur;
				$cnt++;
			}
		}
		else
			$prw[$prv === 'b' ? '3' : ($prv === 'l' ? '1' : '2')]['100']++;

		$jnc['r']['2l'] = $jnew['r']['lp'];
		$jnc['r']['2r'] = $jnew['r']['rp'];
		$jnc['i']['2l'] = $jnew['i']['lp'];
		$jnc['i']['2r'] = $jnew['i']['rp'];

		$jnc['r']['2lw'] = $jnew['r']['lw'];
		$jnc['r']['2rw'] = $jnew['r']['rw'];
		$jnc['i']['2lw'] = $jnew['i']['lw'];
		$jnc['i']['2rw'] = $jnew['i']['rw'];


		$jnc['r']['3'] = $jnew['r']['lr'] + $jnew['r']['rl'];
		$jnc['r']['3e'] = $jnew['r']['bp'];
		$jnc['r']['3w'] = $jnew['r']['bw'];

		$jnc['i']['3'] = $jnew['i']['lr'] + $jnew['i']['rl'];
		$jnc['i']['3e'] = $jnew['i']['bp'];
		$jnc['i']['3w'] = $jnew['i']['bw'];

		$jnc['r']['3d'] = $jnew['r']['lnl'] + $jnew['r']['rnr'];
		$jnc['i']['3d'] = $jnew['i']['lnl'] + $jnew['i']['rnr'];


		$jnc['r']['4n'] = $jnew['r']['ll'] + $jnew['r']['rr'];
		$jnc['i']['4n'] = $jnew['i']['ll'] + $jnew['i']['rr'];

		$jnc['r']['4s'] = $jnew['r']['bb'];
		$jnc['i']['4s'] = $jnew['i']['bb'];

		$jnc['r']['4'] = $jnew['r']['lb'] + $jnew['r']['rb'] + $jnew['r']['bl'] + $jnew['r']['br'];
		$jnc['i']['4'] = $jnew['i']['lb'] + $jnew['i']['rb'] + $jnew['i']['bl'] + $jnew['i']['br'];

		$jnc['r']['4d'] = $jnew['r']['lnr'] + $jnew['r']['lnb'] + $jnew['r']['rnl'] + $jnew['r']['rnb'] +
				$jnew['r']['bnl'] + $jnew['r']['bnr'] + $jnew['r']['bnb'];
		$jnc['i']['4d'] = $jnew['i']['lnr'] + $jnew['i']['lnb'] + $jnew['i']['rnl'] + $jnew['i']['rnb'] +
				$jnew['i']['bnl'] + $jnew['i']['bnr'] + $jnew['i']['bnb'];

		$cnt = $jnew['r']['lnl'] + $jnew['r']['lnb'] + $jnew['r']['bnl'] + $jnew['r']['bnb'] + $jnew['i']['lnl'] + $jnew['i']['lnb'] + $jnew['i']['bnl'] + $jnew['i']['bnb'] ;
		if ($cnt) {
			$parms['r']['le'] -= $cnt;
			$parms['i']['le'] -= $cnt;
			$parms['r']['ld'] = $parms['i']['ld'] = $cnt;
		}
		$cnt = $jnew['r']['rnr'] + $jnew['r']['rnb'] + $jnew['r']['bnr'] + $jnew['r']['bnb'] + $jnew['i']['rnr'] + $jnew['i']['rnb'] + $jnew['i']['bnr'] + $jnew['i']['bnb'] ;
		if ($cnt) {
			$parms['r']['re'] -= $cnt;
			$parms['i']['re'] -= $cnt;
			$parms['r']['rd'] = $parms['i']['rd'] = $cnt;
		}
		$parms['m']['rd'] = $jnc['r']['3d'] + $jnc['r']['4d'] + $jnc['r']['2l'] + $jnc['r']['2r'] + $jnc['r']['2lw'] + $jnc['r']['2rw'] + $jnc['r']['3e'] + $jnc['r']['3w'];
		$parms['m']['ld'] = $jnc['i']['3d'] + $jnc['i']['4d'] + $jnc['i']['2l'] + $jnc['i']['2r'] + $jnc['i']['2lw'] + $jnc['i']['2rw'] + $jnc['i']['3e'] + $jnc['i']['3w'];

		$this->spare = rtrim($this->queryString($jnew), '&');
		$this->l_tbl = rtrim($this->queryString($l), '&');
		$this->r_tbl = rtrim($this->queryString($r), '&');
		$this->tiles = rtrim($this->queryString($parms), '&');
		$this->junc = rtrim($this->queryString($jnc), '&');
		$this->p_raceway = rtrim($this->queryString($prw), '&');
		return parent::beforeSave();
	}

	public function queryString($params, $name = null) {
		$ret = "";
		foreach ($params as $key => $val) {
			if (is_array($val)) {
				if ($name == null)
					$ret .= $this->queryString($val, $key);
				else
					$ret .= $this->queryString($val, $name . "[$key]");
			} else {
				if ($name != null)
					$ret.=$name . "[$key]" . "=$val&";
				else
					$ret.= "$key=$val&";
			}
		}
		return $ret;
	}

}
?>