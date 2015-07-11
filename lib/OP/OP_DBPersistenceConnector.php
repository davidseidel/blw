<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_PersistenceConnector.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_PrimaryKeyFactory.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_AttributeTypes.php");
abstract class WAT_OP_DBPersistenceConnector extends WAT_OP_PersistenceConnector {
	/**
	* @var WAT_DB_Server
	*/
	protected $db_server = null;
	protected $primaryKeyFactory = null;
	
	
	protected function onInstanciate() {
		require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."DB/DB_ServerFactory.php");
		$dsn = WAT_OP_PersistenceConnector::$packages->offsetGet($this->getPackageName())->getConnectorParameter("DB", "dsn");
		$this->db_server =  WAT_DB_ServerFactory::getDbConnection($dsn);
	}
	
	public function getPrimaryKeyFactory() {
		if(!is_object($this->primaryKeyFactory)) {
			$this->primaryKeyFactory = new WAT_OP_PrimaryKeyFactory($this->db_server);
			$this->primaryKeyFactory->setPersistenceConnector($this);
		}
		return $this->primaryKeyFactory;
	}
	
	public function onLoad() {		
		// instanciate object
		$object = $this->getInstance();
		
		// get primary-key
		$primary_key = $object->__getPrimaryKey();

		
		if(count($primary_key) != func_num_args()) {
			throw new Exception("The Object of Class has exactly ".count($primary_key)." primary key attributes but ".func_num_args()." given.");
		}
		
		// get the arguments of the method
		$primary_key_values = func_get_args();

		
		// storage for the primary-key-parameters
		$primary_key_parameter = array();

		// create the sql-statement

		// add every primary-key-attribute to the query
		// in the way <name>=?<count>
		$primary_key_count = 0;
		foreach ($primary_key as $primary_key_attribute) {
			++$primary_key_count;
			$primary_key_parameter[] = $primary_key_attribute->getName()."=?".$primary_key_count;
		}
		// add the primary-key to the statement
		$query = "SELECT * FROM ?0 WHERE ".implode(" AND ", $primary_key_parameter);

		// instanciate the statement-object
		$stmt = $this->db_server->prepareStatement($query);

		// limit the result of the statement
		$stmt->setMaxRows(1);

		// add the object-name to the statement
		$stmt->setString(0, $object->__getName());

		// add the value of the primary key to the statement
		$primary_key_count = 0;
		foreach ($primary_key as $primary_key_attribute) {
			++$primary_key_count;
			// get the base-type of the attribute-type
			$base_type = WAT_OP_AttributeTypes::getBaseType($primary_key_attribute->getType());

			// get the value of the primary-key
			if($primary_key_count <= func_num_args()) {
				$primary_key_value = $primary_key_values[$primary_key_count - 1];
			} else {
				$primary_key_value = null;
			}

			// check if the value of the primary isn't null
			if($primary_key_value == null)  {
				throw new Exception("Found Primary-Key with value Null!!! (name='".$primary_key_attribute->getName()."')");
			}

			// run the required method to add the value to the statement
			switch($base_type) {
				case "STRING" : {
					$stmt->setString($primary_key_count, $primary_key_value, true);
					break;
				}
				case "INTEGER" : {
					$stmt->setInteger($primary_key_count, $primary_key_value);
					break;
				}
				default : {
					throw new Exception("Base-Type '".$base_type."' not supported");
				}
			}
		}

		// execute the query
		$result = $stmt->execute();

		if(!($result->getRowCount() > 0)) {
			throw new Exception("Couldn't load object!!!!");
		}

		
		// set the value of the result-row to the aggregated attribute
		foreach ($result as $result_row) {
			$object = $this->getObjectByRow($result_row);
		}

		$object->__setLoaded(true);
		// return success
		
		return $object;
	}
	
	public function loadAll() {
		// get the primary_key
		$pkey = WAT_OP_PersistenceConnector::$packages[$this->package_name]->getObjectByName($this->object_name)->__getPrimaryKey();
		
		// get names of the primary_key
		$pkey_names = array_keys($pkey);	
		
		// build the statement
		$query = 'SELECT '.implode(',',$pkey_names).' FROM ?0';

		// instanciate the statement-object
		$stmt = $this->db_server->prepareStatement($query);

		// add the object-name to the statement
		$stmt->setString(0, $this->object_name);
		

		// execute the query
		$result = $stmt->execute();
		
		if(!($result->getRowCount() > 0)) {
			return $objects;
		}
		
		// storage for the loaded objects
		$objects = array();
		
		// set the value of the result-row to the aggregated attribute
		foreach ($result as $result_row) {
			$pkey_value = array();
	
			foreach ($pkey as $attribute) {
				$base_type = WAT_OP_AttributeTypes::getBaseType($attribute->getType());
				$value = null;
				switch($base_type) {
					case "STRING" : {
						$pkey_value[] = $result_row->getString($attribute->getName());
						break;
					}
					case "INTEGER" : {
						$pkey_value[] = $result_row->getInteger($attribute->getName());
						break;
					}
					default : {
						throw new Exception("Base-Type '".$base_type."' not supported");
					}
				}
				// set the value to the attribute
			}
			$objects[] = call_user_func_array(array($this,'load'),   $pkey_value);
		}
		
		// return objects
		return $objects;
	}
	
	public function getObjectByRow(WAT_DB_ResultRow $result_row) {	
		$pkey_names = array_keys(WAT_OP_PersistenceConnector::$packages[$this->package_name]->getObjectByName($this->object_name)->__getPrimaryKey());	
		$pkey_values = array();
		foreach ($pkey_names as $pkey_name) {
			$pkey_values[] = $result_row->getString($pkey_name);
		}
		
		$object = call_user_func_array(array($this, "create"),  $pkey_values);
		
		$attributes = $object->__getAttributes();
		foreach ($attributes as $attribute) {
			if($object->__isLoaded() && in_array($attribute->getName(), $pkey_names)) {
				continue;
			}
			$base_type = WAT_OP_AttributeTypes::getBaseType($attribute->getType());
			$value = null;
			switch($base_type) {
				case "STRING" : {
					$value = $result_row->getString($attribute->getName());
					break;
				}
				case "INTEGER" : {
					$value = $result_row->getInteger($attribute->getName());
					break;
				}
				default : {
					throw new Exception("Base-Type '".$base_type."' not supported");
				}
			}
			// set the value to the attribute
			$attribute->setValue($value);
		}
		$object->__setLoaded(true);
		return $object;
	}

	public function onStore(WAT_OP_Object $object)  {
		if($object->__isLoaded()) {
			$result = $this->update($object);
		} else {
			$result = $this->insert($object);
		}
		
		if($result) {
			$this->updateRelations($object);
			return $result;
		}
		
	}

	protected function insert(WAT_OP_Object $object) {
		// get attributes
		$attributes = $object->__getAttributes();

		// storage for the attribute-parameters
		$attribute_parameter = array();

		// name of the columns
		$column_names = array();

		// create the sql-statement
		$query = "INSERT INTO ?0({columns}) VALUES({values})";

		// generate parameter of every attribute for query
		// an store every name of the attribute as column-name
		$attribute_count = 0;
		foreach ($attributes as $attribute) {
			++$attribute_count;
			array_push($column_names, $attribute->getName());
			$attribute_parameter[] = "?".$attribute_count;
		}

		// concat every column-name with a ','
		$column_names_string = implode(",", $column_names);

		// concat every parameter with a ','
		$attribute_parameter_string = implode(",", $attribute_parameter);

		// replace with-spaces in query
		// {columns} with column_names_string
		// {values} with attribute-parameter-string
		$query = str_replace(array("{columns}", "{values}"), array($column_names_string, $attribute_parameter_string), $query);

		// instanciate the statement-object
		$stmt = $this->db_server->prepareStatement($query);

		// add the object-name to the statement as parameter 0
		$stmt->setString(0, $object->__getName());

		// add every attribute-value to the associated parameter
		$attribute_count = 0;
		foreach ($attributes as $attribute) {
			++$attribute_count;
			$base_type = WAT_OP_AttributeTypes::getBaseType($attribute->getType());
			switch($base_type) {
				case "STRING" : {
					$stmt->setString($attribute_count, (string) $attribute->getValue(), true);
					break;
				}
				case "INTEGER" : {
					$stmt->setInteger($attribute_count, (int) $attribute->getValue());
					break;
				}
				default : {
					throw new Exception("Base-Type '".$base_type."' not supported");
				}
			}
		}

		// execute the query and return the result
		return $stmt->execute();
	}

	protected function update(WAT_OP_Object $object) {
		// get attributes
		$attributes = $object->__getAttributes();

		// storage for the primary-key-parameters
		$primary_key_parameter = array();

		// storage for the other attributes
		$attribute_parameter = array();

		// create the sql-statement
		$query = "UPDATE ?0 SET {columns} WHERE {primary_key}";

		// add every primary-key-attribute to the query
		// in the way <name>=?<count>
		$attribute_count = 0;
		foreach ($attributes as $attribute) {
			++$attribute_count;
			if($object->__isPrimaryKey($attribute)) {
				$primary_key_parameter[] = $attribute->getName()."=?".$attribute_count;
			} else {
				$attribute_parameter[] = $attribute->getName()."=?".$attribute_count;
			}
		}

		$attribute_parameter_string = implode(",", $attribute_parameter);
		$primary_key_parameter_string = implode(" AND ", $primary_key_parameter);

		$query = str_replace(array("{columns}", "{primary_key}"), array($attribute_parameter_string, $primary_key_parameter_string), $query);

		$stmt = $this->db_server->prepareStatement($query);

		$stmt->setString(0, $object->__getName());

		// add every attribute-value to the associated parameter
		$attribute_count = 0;
		foreach ($attributes as $attribute) {
			++$attribute_count;
			$base_type = WAT_OP_AttributeTypes::getBaseType($attribute->getType());
			switch($base_type) {
				case "STRING" : {
					$stmt->setString($attribute_count, $attribute->getValue(), true);
					break;
				}
				case "INTEGER" : {
					$stmt->setInteger($attribute_count, $attribute->getValue());
					break;
				}
				default : {
					throw new Exception("Base-Type '".$base_type."' not supported");
				}
			}
		}

		// execute the query and return the result
		return $stmt->execute();
	}

	public function onRemove(WAT_OP_Object $object) {
		// storage for the primary-key-parameters
		$primary_key_parameter = array();

		// get primary-key
		$primary_key = $object->__getPrimaryKey();

		// storage for the primary-key-parameters
		$primary_key_parameter = array();

		// create the sql-statement

		// add every primary-key-attribute to the query
		// in the way <name>=?<count>
		$primary_key_count = 0;
		foreach ($primary_key as $primary_key_attribute) {
			++$primary_key_count;
			$primary_key_parameter[] = $primary_key_attribute->getName()."=?".$primary_key_count;
		}
		// add the primary-key to the statement
		$query = "DELETE FROM ?0 WHERE ".implode(" AND ", $primary_key_parameter);

		// instanciate the statement-object
		$stmt = $this->db_server->prepareStatement($query);

		// limit the result of the statement
		$stmt->setMaxRows(1);

		// add the object-name to the statement
		$stmt->setString(0, $object->__getName());

		// add the value of the primary key to the statement
		$primary_key_count = 0;
		foreach ($primary_key as $primary_key_attribute) {
			++$primary_key_count;
			// get the base-type of the attribute-type
			$base_type = WAT_OP_AttributeTypes::getBaseType($primary_key_attribute->getType());

			// get the value of the primary-key
			$primary_key_value = $primary_key_attribute->getValue();

			// check if the value of the primary isn't null
			if($primary_key_value == null)  {
				throw new Exception("Found Primary-Key with value Null!!! (name='".$primary_key_attribute->getName()."')");
			}

			// run the required method to add the value to the statement
			switch($base_type) {
				case "STRING" : {
					$stmt->setString($primary_key_count, $primary_key_value, true);
					break;
				}
				case "INTEGER" : {
					$stmt->setInteger($primary_key_count, $primary_key_value);
					break;
				}
				default : {
					throw new Exception("Base-Type '".$base_type."' not supported");
				}
			}
		}

		// execute the query and return the result
		$result = $stmt->execute();
		if($result) {
			return $this->updateRelations($object, true);
		}
	}
	
	public function loadRelatedObjects($relation_name, WAT_OP_Object $object,  $attribute_name, $related_object_name, $related_attribute_name) {
		// get primary key of object
		$object_pkey = $object->__getPrimaryKey();
		
		// build key-string for query
		$primary_key_parameter = array();
		$key_count = 0;
		foreach ($object_pkey as $primary_key_attribute) {
			++$key_count;
			$primary_key_parameter[] = $related_object_name."_".$related_attribute_name."_".$primary_key_attribute->getName()."=?".$key_count;
		}
		
		// build column-string for related object
		$related_object_pkey = self::$packages->offsetGet($this->getPackageName())->getObjectByName($related_object_name)->__getPrimaryKey();
		$related_primary_key_parameter = array();
		foreach ($related_object_pkey as $primary_key_attribute) {
			$related_primary_key_parameter[] = $object->__getName()."_".$attribute_name."_".$primary_key_attribute->getName();
		}
		
		
		// build sql-query
		$query = "SELECT ".implode(",", $related_primary_key_parameter)." FROM ?0 WHERE ".implode(",", $primary_key_parameter);
		
		// prepare statement
		$stmt = $this->db_server->prepareStatement($query);
		
		// add the object-name to the statement
		$rel_table_name = $this->package_name."_".str_replace("-", "_", $relation_name);
		$stmt->setString(0, $rel_table_name);
		
		$key_count = 0;
		foreach ($object_pkey as $primary_key_attribute) {
			++$key_count;
			// get the base-type of the attribute-type
			$base_type = WAT_OP_AttributeTypes::getBaseType($primary_key_attribute->getType());

			// get the value of the primary-key
			$primary_key_value = $primary_key_attribute->getValue();
			

			// check if the value of the primary isn't null
			if($primary_key_value == null)  {
				throw new Exception("Found Primary-Key with value Null!!! (name='".$primary_key_attribute->getName()."')");
			}

			// run the required method to add the value to the statement
			switch($base_type) {
				case "STRING" : {
					$stmt->setString($key_count, $primary_key_value, true);
					break;
				}
				case "INTEGER" : {
					$stmt->setInteger($key_count, $primary_key_value);
					break;
				}
				default : {
					throw new Exception("Base-Type '".$base_type."' not supported");
				}
			}
		}
		
		// execute the statement
		$result = $stmt->execute();
		
		if(!($result->getRowCount() > 0)) {
			return array();
		}
		
		// instanciate related connector
		$related_object_connector_name = $this->package_name."_".$related_object_name."Connector";
		$related_connector = new $related_object_connector_name();
		
		// storage for the related objects
		$related_objects = array();
		
		// walk thru all found ids an load there objects
		foreach ($result as $result_row) {
			$related_key_values = array();
			foreach ($related_object_pkey as $related_object_pkey_attribute) {
				$base_type = WAT_OP_AttributeTypes::getBaseType($primary_key_attribute->getType());
				
				// check if the value of the primary isn't null
				if($primary_key_value == null)  {
					throw new Exception("Found Primary-Key with value Null!!! (name='".$primary_key_attribute->getName()."')");
				}
	
				// run the required method to get the value from the statement
				switch($base_type) {
					case "STRING" : {
						$related_key_values[] = $result_row->getString($object->__getName()."_".$attribute_name."_".$related_object_pkey_attribute->getName());
						break;
					}
					case "INTEGER" : {
						$related_key_values[] = $result_row->getInteger($object->__getName()."_".$attribute_name."_".$related_object_pkey_attribute->getName());
						break;
					}
					default : {
						throw new Exception("Base-Type '".$base_type."' not supported");
					}
				}
			}
			$related_objects[] = call_user_func_array(array($related_connector, "load"), $related_key_values);
		}
		
		return $related_objects;
	}
	
	public function updateRelations(WAT_OP_Object $object, $only_remove = false) {
		// get the primary_key
		$pkey = $object->__getPrimaryKey();
		
		// get all relations
		$relations = self::$packages->offsetGet($this->getPackageName())->getRelationsByObject($object);
		
		// if nothing to do go home :-)
		if(!is_array($relations)) {
			return false;
		}
		
		// get the Names of the related attributes
		$attribute_names = array_keys($relations);
		
		
		// got thru relations and delete all relations
		foreach ($attribute_names as $attribute_name) {
			$relation_name = $relations[$attribute_name]->getName();
			$attribute = $object->__getAttributeByName($attribute_name);
			$attribute->loadObjects();
			
			$related_object_name = $attribute->getRelatedObjectName();
			$related_attribute_name = $attribute->getRelatedName();
			
			// build key-string for query
			$primary_key_parameter = array();
			$key_count = 0;
			foreach ($pkey as $primary_key_attribute) {
				++$key_count;
				$primary_key_parameter[] = $related_object_name."_".$related_attribute_name."_".$primary_key_attribute->getName()."=?".$key_count;
			}
			
			// build the query
			$query = "DELETE FROM ?0 WHERE ".implode(' AND ', $primary_key_parameter);
			
			// prepare statement
			$stmt = $this->db_server->prepareStatement($query);
			
			// add the object-name to the statement
			$rel_table_name = $this->package_name."_".str_replace("-", "_", $relation_name);
			$stmt->setString(0, $rel_table_name);
			
			
			$key_count = 0;
			foreach ($pkey as $primary_key_attribute) {
				++$key_count;
				// get the base-type of the attribute-type
				$base_type = WAT_OP_AttributeTypes::getBaseType($primary_key_attribute->getType());
	
				// get the value of the primary-key
				$primary_key_value = $primary_key_attribute->getValue();
				
	
				// check if the value of the primary isn't null
				if($primary_key_value == null)  {
					throw new Exception("Found Primary-Key with value Null!!! (name='".$primary_key_attribute->getName()."')");
				}
	
				// run the required method to add the value to the statement
				switch($base_type) {
					case "STRING" : {
						$stmt->setString($key_count, $primary_key_value, true);
						break;
					}
					case "INTEGER" : {
						$stmt->setInteger($key_count, $primary_key_value);
						break;
					}
					default : {
						throw new Exception("Base-Type '".$base_type."' not supported");
					}
				}
			}
			
			//execute the statement
			$stmt->execute();
			
			// if we only want to remove the relation stop here
			if($only_remove) {
				break;
			}
			
			
			
			$related_objects = $object->__getAttributeByName($attribute_name)->getValue();
			foreach ($related_objects as $related_object) {
				
				$related_object_pkey = $related_object->__getPrimaryKey();
				
				$key_count = 0;
				
				$columns = array();
				$values = array();
				$value_parameters = array();
				$types = array();
				
				
				foreach ($related_object_pkey as $primary_key_attribute) {
					++$key_count;
					$columns[$key_count] = $attribute->getObjectName()."_".$attribute->getName()."_".$primary_key_attribute->getName();
					$values[$key_count] = $primary_key_attribute->getValue();
					$value_parameters[] = "?".$key_count;
					$types[$key_count] = WAT_OP_AttributeTypes::getBaseType($primary_key_attribute->getType());
				}
				
				foreach ($pkey as $primary_key_attribute) {
					++$key_count;
					$columns[$key_count] = $attribute->getRelatedObjectName()."_".$attribute->getRelatedName()."_".$primary_key_attribute->getName();
					$values[$key_count] =  $primary_key_attribute->getValue();
					$value_parameters[] = "?".$key_count;
					$types[$key_count] = WAT_OP_AttributeTypes::getBaseType($primary_key_attribute->getType());
				}
				
				$column_string = implode(",", $columns);
				$value_string = implode(",", $value_parameters);
				
				// build the query
				$query = 'INSERT INTO ?0('.$column_string.') VALUES('.$value_string.')';
				
				// prepare statement
				$stmt = $this->db_server->prepareStatement($query);
				
				// insert the table-table of the relation-table in the statement
				$stmt->setString(0, $rel_table_name);
				
				// insert the values in the statement
				$key_count = 0;
				foreach ($values as $value) {
					++$key_count;
					$base_type = $types [$key_count];
					switch($base_type) {
						case "STRING" : {
							$stmt->setString($key_count, $value, true);
							break;
						}
						case "INTEGER" : {
							$stmt->setInteger($key_count, $value);
							break;
						}
						default : {
							throw new Exception("Base-Type '".$base_type."' not supported");
						}
					}
				}
				
				// execute the statement
				$stmt->execute();
			}
			
		}
		return true;
	}
}
?>