<?php
class WAT_OP_AttributeTypes {
	const TEXT 				= "TEXT";
	const INTEGER 			= "INTEGER";
	const INTEGER_UNSIGNED	= "INTEGER_UNSIGNED";
	const NUMBER 				= "NUMBER";
	const NUMBER_UNSIGNED 	= "NUMBER_UNSIGNED";
	const DATE 				= "DATE";
	const URL_OR_IP 			= "URL_OR_IP";
	const EMAIL 				= "EMAIL";
	const PHONE 				= "PHONE";
	const UNDEFINED			= "UNDEFINED";
	
	protected static $regex = array(
		self::TEXT  => '=^.+$=',
		self::INTEGER => '=^[+-]?\d+$=',
		self::INTEGER_UNSIGNED => '=^\d+$=',
		self::NUMBER => '=^[+-]?(\d+\.?\d+)|(\d+)$=',
		self::NUMBER_UNSIGNED  => '=^(\d+\.?\d+)|(\d+)$=',
		self::DATE  => '=^((\d{2}(([02468][048])|([13579][26]))[\-\/\s]?((((0?[13578])|(1[02]))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\-\/\s]?((0?[1-9])|([1-2][0-9])))))|(\d{2}(([02468][1235679])|([13579][01345789]))[\-\/\s]?((((0?[13578])|(1[02]))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\-\/\s]?((0?[1-9])|(1[0-9])|(2[0-8]))))))(\s(((0?[1-9])|(1[0-2]))\:([0-5][0-9])((\s)|(\:([0-5][0-9])\s))([AM|PM|am|pm]{2,2})))?$='	,
		self::EMAIL => '=^\+?[a-z0-9](([-+.]|[_]+)?[a-z0-9]+)*@([a-z0-9]+(\.|\-))+[a-z]{2,6}$=',		
		self::URL_OR_IP => '=^(http(s?)|ftp)\:\/\/(([a-z0-9\�\�\�\-]+\.)+[a-z]{2,6}|(\d{1,3}\.){3}\d{1,3}(\:\d+)?)\.?([\/\?].*)$=',
		self::PHONE  => '=^(\+|[0]{1,2})[1-9\ ]+\d*[ ]*[-\ \/\.][ ]*[1-9]{1}[0-9\ ]*\d*$='
		);
	
	protected static $base_types = array(
		self::TEXT  => 'STRING',
		self::UNDEFINED => 'STRING',
		self::INTEGER => 'INTEGER',
		self::INTEGER_UNSIGNED => 'INTEGER',
		self::NUMBER => 'FLOAT',
		self::NUMBER_UNSIGNED  => 'FLOAT',
		self::DATE  => 'STRING'	,
		self::EMAIL => 'STRING',		
		self::URL_OR_IP => 'STRING',
		self::PHONE  => 'STRING'
	);
	
	static function getRegex($type) {
		if(array_key_exists($type, self::$regex)) {
			return self::$regex[$type];
		} else {
			throw new Exception("No regular-expression found for type '".$type."'");
		}
	}
	
	static function getTypes(){
		$reflection = new ReflectionClass(__CLASS__);
		return $reflection->getConstants();
	}
	
	static function getBaseType($type) {
		if(array_key_exists($type, self::$base_types)) {
			return self::$base_types[$type];
		} else {
			throw new Exception("No base-type found for '".$type."'");
		}
	}
}
?>