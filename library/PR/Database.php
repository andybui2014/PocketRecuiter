<?php
class PR_Database {
	const TABLE_config = "config";
    
	private static $db = null;

	private static function init() {
		if (self::$db !== null) return;
		self::$db = Zend_Registry::get("DB");
	}

	public static function getInstance() {
		self::init();
		return self::$db;
	}

	public static function getFieldList($name) {
		$fieldList = array_keys(self::describeTable($name));

		return $fieldList;
	}

	public static function getFieldListType($name) {
		$fields = self::describeTable($name);
		$fieldListType = array();
		foreach ($fields as $name=>$info) {
			$fieldListType[$name] = $info["DATA_TYPE"];
		}
		return $fieldListType;
	}

	private static function describeTable($name) {

		self::init();
		$tableDesign = self::$db->describeTable($name);
		return $tableDesign;
	}

	public static function convertQueryResult($result, $fieldListType) {
		// converts query results to what caspio api returned
		if (!is_array($fieldListType)) return $result;
		foreach ($result as $k=>$v) {
			if ($v === "NULL")
				$result[$k] = null;
			else if (array_key_exists($k, $fieldListType)) {
				switch ($fieldListType[$k]) {
					case "tinyint":
						$result[$k] = $v === "1" || $v === 1 || $v === "True" || $v === true ? "True" : "False";
						break;
					case "datetime":
						if ($t = strtotime($v))
							$result[$k] = date('m/d/Y H:i:s', $t);
						break;
					case "date":
						if ($t = strtotime($v))
							$result[$k] = date('m/d/Y', $t);
						break;
				}
			}
		}
		return $result;
	}

	private static function prepareData($data, $fieldListType) {
		// prepares data for update or insert into db
		if (!is_array($fieldListType)) return $data;
        
        $retData = array();
		foreach ($data as $key => &$val) {
			if (!array_key_exists($key, $fieldListType)) continue;
            
            $retData[$key] = $data[$key];
			if ($val === NULL || $val === "") {
				$val = new Zend_Db_Expr('DEFAULT');
				continue;
			}
            
			switch ($fieldListType[$key]) {
				case "tinyint":
					$val = $val == 1 || $val === "True" || $val === true ? 1 : 0;                    
					break;
				case "datetime":
					if ($t = strtotime($val))
						$val = date('Y-m-d H:i:s', $t);
					break;
				case "date":
					if ($t = strtotime($val))
						$val = date('Y-m-d', $t);
					break;
			}
            $retData[$key] = $val;
        }
		return $retData;
	}

	public static function insert($table, $fields) {
		self::init();
		$result = false;
		$fieldListType = self::getFieldListType($table);
		try {
			$fields = self::prepareData($fields, $fieldListType);
			$result = self::$db->insert($table, $fields);
		} catch (Exception $e) {
			$errors = PR_Api_Error::getInstance();
			$errors->addError(7, 'SQL Error: ' . $e);
			error_log($e);
			$result = false;
		}
		return $result;
	}

	public static function update($table, $fields, $criteria) {
		self::init();
		$result = false;
		$fieldListType = self::getFieldListType($table);
		try {
            $fields = self::prepareData($fields, $fieldListType);            
			$result = self::$db->update($table, $fields, $criteria);            
		} catch (Exception $e) {
			$errors = PR_Api_Error::getInstance();
			$errors->addError(7, 'SQL Error: ' . $e);
			error_log($e);
			$result = false;
		}
		return $result;
	}

	public static function fetchAll(Zend_Db_Select $select, &$count = null) {
		self::init();
		$result = false;
		try {
			$limit = $select->getPart(Zend_Db_Select::LIMIT_COUNT);
			$offset = $select->getPart(Zend_Db_Select::LIMIT_OFFSET);
			$result = $select->query()->fetchAll();
            if ($count !== null) {
                $count = self::getCount($select);
            }
		} catch (Exception $e) {
			$errors = PR_Api_Error::getInstance();
			$errors->addError(7, 'SQL Error: ' . $select . " " . $e);
			error_log($e);
			$result = false;
		}

		return $result;
	}

	public static function getCount(Zend_Db_Select $select)
	{
		$countSelect = clone $select;
		$countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
		$countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::ORDER);

        /**
         * We can't drop columns if we have "HAVING","DISTINCT" or "GROUP" statements.
         * This is really resource heavy, however we don't use Zend mysql extension for SQL_CALC_FOUND_ROWS
         * @author Pavel Shutin
         *
         */
        if ($countSelect->getPart(Zend_Db_Select::DISTINCT) || 
                $countSelect->getPart(Zend_Db_Select::HAVING) !=array() ||
                $countSelect->getPart(Zend_Db_Select::GROUP) !=array()) {
            return count(self::$db->fetchAll($countSelect));
        }else{
            $countSelect->reset(Zend_Db_Select::COLUMNS);
            $countSelect->columns(new Zend_Db_Expr('COUNT(*) AS count'));
            return self::$db->fetchOne($countSelect);
        }
	}

	public static function whereProximity(Zend_Db_Select $select, $centerLat, $centerLong, $fieldLat, $fieldLong, $distance, $calculatedDistanceAs = "calcDistance") {
		self::init();
		if (empty($centerLat) || empty($centerLong) || empty($fieldLat) || empty($fieldLong) || empty($distance)) return $select;
		$centerLat = floatval($centerLat);
		$centerLong = floatval($centerLong);
		$distance = floatval($distance);

		if ($distance >= 0 && $distance <= 12450.775) {

			$longOffset = $distance / abs(cos(deg2rad($centerLat)) * 69);

			$rectLong1 = $centerLong - $longOffset;
			$rectLong2 = $centerLong + $longOffset;

			$latOffset = $distance / 69;

			$rectLat1 = $centerLat - $latOffset;
			$rectLat2 = $centerLat + $latOffset;

            /**
             * @author Pavel Shutin
             */
            $select->where("$fieldLat >= ? ", $rectLat1)
				->where("$fieldLat <= ? ", $rectLat2)
				->where("$fieldLong >= ? ", $rectLong1)
				->where("$fieldLong <= ? ", $rectLong2);
		}


		if (!empty($calculatedDistanceAs)) {
			/* Distance Formula : 3956 * 2 * ASIN(SQRT( POWER(SIN((orig.lat - dest.lat) * pi()/180 / 2), 2) + COS(orig.lat * pi()/180) * COS(dest.lat * pi()/180) * POWER(SIN((orig.lon -dest.lon) * pi()/180 / 2), 2) ))as distance */
			$earthDiameter = 7912;
			$piOver180 = 0.01745329;
			$select->columns(array($calculatedDistanceAs => new Zend_Db_Expr("$earthDiameter * ASIN(SQRT( POWER(SIN(($centerLat - $fieldLat) * $piOver180 / 2), 2) + COS($centerLat * $piOver180) * COS($fieldLat * $piOver180) * POWER(SIN(($centerLong - $fieldLong) * $piOver180 / 2), 2) ))")));
            /**
             * @author Pavel Shutin
             */
            $select->having($calculatedDistanceAs.' <= ?',$distance);
		}
        //var_dump($select->__toString());exit;
		return $select;
	}
}
