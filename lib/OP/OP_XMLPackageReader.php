<?php
// include required libaries
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_PackageReader_Interface.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_PackageMetadata.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_ObjectMetadata.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_PersistenceConnector.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_RelationMetadata.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_AttributeMetadata.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'../CACHE/CacheFactory.php');

/**
 * Reader for XML-based package-descriptors
 * @package wat.op
 */
class WAT_OP_XMLPackageReader implements WAT_OP_PackageReader_Interface {
	/**
	* @desc object of the xml (SimpleXML)
	* @var SimpleXMLElement xml
	*/
	protected $objects = null;
	/**
	* @desc object of the Package
	* @var WAT_OP_Package_Interface
	*/
	protected $package = null;
	/**
	* @desc source of the xml-file
	* @var string
	*/
	protected $xml = null;

	/**
	 * Constructor
	 * @example __construct(xml:///path/to/package/package.xml)
	 * @param string $dsn
	 */
	public function __construct($dsn) {
		// parse dsn
		$dsn_regex = "=^xml://(.*)$=";
		preg_match($dsn_regex, $dsn, $dsn_parts);

		$cache = WAT_CACHE_CacheFactory::getCacheByDSN(CACHE_DSN);

		// process location (replace the directory-separator)
		// and store it
		$location = str_replace("/", DIRECTORY_SEPARATOR, $dsn_parts[1]);

		$cached = false;

		// check if the deskriptor exists and try to load it
		if(is_file($location)) {
			// generate the cache key
			$cache_key = md5($dsn.filemtime($location));
			if(!is_null($cache)) {
				$package = unserialize($cache->get($cache_key));
				if($package) {
					$this->package = $package;
					$cached = true;
				}
			}

			if(!$cached && $xml = simplexml_load_file($location)) {
				// store xml-object
				$this->xml = $xml;

				// instantiate package
				$this->package = new WAT_OP_PackageMetadata();

				// get package-name
				$this->package->setName($this->readName());

				// get package-version
				$this->package->setVersion($this->readVersion());

				// retrieve and store the object-data
				$this->objects = $this->readObjects();
				foreach ($this->objects as $object) {
					$this->package->addObject($object);
				}

				// set all parameter for the connectors
				$this->package->setConnectorParameters($this->readConnectors());

				// retrieve and store the relation-data
				$relations = $this->readRelations();
				foreach ($relations as $relation) {
					$this->package->addRelation($relation);
				}
				if(!is_null($cache)) {
					$cache->put($cache_key, serialize($this->package));
				}
			}
			WAT_OP_PersistenceConnector::addPackageMetadata($this->package);
		} else {
			throw new Exception("Could not read descriptor-file from ".$location);
		}
	}


	/**
	 * reading all connector-parameters and return them
	 *
	 * @return array()
	 */
	protected function readConnectors() {
		$connector_params = array();
		$tag_name = "connectors";
		$connectors = $this->xml->$tag_name;
		foreach ($connectors->children() as $connector) {
			$connector_name = (string) $connector['name'];
			$connector_params[$connector_name] = array();
			foreach ($connector->children() as $param) {
				$param_name = (string) $param['name'];
				$connector_params[$connector_name][$param_name] = (string) $param;
			}
		}
		return $connector_params;
	}

	/**
	 * reads the name of the package from the descriptor-file
	 *
	 * @return string name of the Package
	 */
	protected function readName() {
		$tag_name = "package-name";
		$name = $this->xml->children()->$tag_name;
		if(trim($name) == '') {
			throw new Exception("No package-name defined");
		} else {
			return (string) $name;
		}
	}

	/**
	 * reads the version of the package from the descriptor-file
	 *
	 * @return string version of the Package
	 */
	protected function readVersion() {
		$tag_name = "package-version";
		$version = $this->xml->children()->$tag_name;
		if(trim($version) == '') {
			throw new Exception("No package-version defined");
		} else {
			return (string) $version;
		}
	}


	/**
	 * Reads all objects from the descriptor and return them
	 *
	 * @return array of WAT_OP_ObjectMetadata-objects
	 */
	protected function readObjects() {
		$objects = array();

		// get all object-tags
		$objects_tag = $this->xml->children()->objects;
		foreach ($objects_tag->children() as $object_tag) {
			// instanciate the metadata-object
			$object = new WAT_OP_ObjectMetadata();

			// extract and store the name of the object
			$tag_name = "object-name";
			$object->__setName((string) $object_tag->$tag_name);

			// extract and store the version of the object
			$tag_name = "object-version";
			$object->__setVersion((string) $object_tag->$tag_name);

			// extract and store the autocommit-flag
			$tag_name = "autocommit";
			$autocommit = (string) $object_tag->$tag_name;
			if($autocommit == "true") {
				$object->__setAutocommit(true);
			} else {
				$object->__setAutocommit(false);
			}

			// lookup and store the column-names of primary-key
			$tag_name = "primary-key";
			$primary_key_column_tags = $object_tag->$tag_name->children();
			$primary_key_columns = array();

			foreach ($primary_key_column_tags as $primary_key_column_tag) {
				array_push($primary_key_columns, (string) $primary_key_column_tag);
			}


			// read the attributes
			$attributes = $this->readAttributes($object_tag);
			foreach ($attributes as $attribute) {
				$object->__addAttribute($attribute);
				if(in_array($attribute->getName(), $primary_key_columns)) {
					$object->__addToPrimaryKey($attribute);
				}
			}

			// store the object to the return value
			array_push($objects, $object);
		}
		return $objects;
	}

	/**
	 * Reads all attributes from the descriptor and return them
	 *
	 * @param SimpleXMLElement $object_xml
	 * @return array of WAT_OP_AttributeMetadata-Objects
	 */
	protected function readAttributes(SimpleXMLElement $object_xml) {
		$attributes = array();

		// get all attribute-tags
		$attributes_tag = $object_xml->attributes;
		foreach ($attributes_tag->children() as $attribute_tag) {
			// instanciate the metadata-object
			$attribute = new WAT_OP_AttributeMetadata();

			// lookup the name of the attribute
			$tag_name = "attribute-name";
			$attribute->setName((string) $attribute_tag->$tag_name);

			// lookup the type of the attribute
			$tag_name = "attribute-type";
			$attribute->setType((string) $attribute_tag->$tag_name);

			// lookup the sql-type of the attribute
			$tag_name = "attribute-sql-type";
			$attribute->setSqlType((string) $attribute_tag->$tag_name);

			// lookup the maximum length of the attribute
			$tag_name = "attribute-max-length";
			$attribute->setMaxLength((int) $attribute_tag->$tag_name);

			// lookup if the attribute is required
			$tag_name = "attribute-required";
			$required_value = (string) $attribute_tag->$tag_name;
			if($required_value == "true") {
				$attribute->setRequired(true);
			} else {
				$attribute->setRequired(false);
			}

			// store the attribute to the return-value
			array_push($attributes, $attribute);
		}
		return $attributes;
	}

	function readRelations() {
		$relations = array();
		$tag_name = "relations";
		$relation_tags = $this->xml->$tag_name;
		// if nothing to do go home :-)
		if(!array_key_exists('relation',  (array) $relation_tags)) {
			return array();
		}
		$i = 0;

		foreach ($relation_tags->relation as $relation_tag) {
			$i++;
			$relations[$i] = new WAT_OP_RelationMetadata();

			// get the name of the relation
			$tag_name = "relation-name";
			$relations[$i]->setName((string) $relation_tag->$tag_name);

			// get the relation-roles
			$tag_name = "relationship-role";
			$role_tags = $relation_tag->$tag_name;

			// get the role-names
			$tag_name = "relationship-role-name";
			$relations[$i]->setNameForRole1((string) $role_tags[0]->$tag_name);
			$relations[$i]->setNameForRole2((string) $role_tags[1]->$tag_name);

			// get the object-names
    			$tag_name = "relationship-role-object";
    			$relations[$i]->setObjectNameForRole1((string) $role_tags[0]->$tag_name);
			$relations[$i]->setObjectNameForRole2((string) $role_tags[1]->$tag_name);

			// get the multiplicities
			$tag_name = "multiplicity";
    			$relations[$i]->setMultiplicityForRole1((string) $role_tags[0]->$tag_name);
			$relations[$i]->setMultiplicityForRole2((string) $role_tags[1]->$tag_name);

			// get the attribute names
			$tag_name = "relationship-attribute-name";
			$relations[$i]->setAttributeNameForRole1((string) $role_tags[0]->$tag_name);
			$relations[$i]->setAttributeNameForRole2((string) $role_tags[1]->$tag_name);
		}
		return $relations;
	}

	/**
	 * returns the object-tree of the package
	 *
	 * @return WAT_OP_Package_Interface
	 */
	public function getPackage() {
		return $this->package;
	}
}

?>