<?php

/**
 * The PlinthDBSchema extends some of the rules and generations
 * from the CDbSchema class
 */
abstract class PlinthDBSchema extends CMysqlSchema
{
	/**
	 * @var array the abstract column types mapped to physical column types.
	 * @since 1.1.6
	 */
    public $columnTypes=array(
        'pk' => 'bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
        'id' => 'bigint(20) NOT NULL',
        'id_null' => 'bigint(20) NULL',
        'guid'=> 'varchar(40) NOT NULL',
        'guild_null'=>'varchar(40) NULL',
        'title' => 'varchar(150) NOT NULL',
        'description'=>'varchar(500) NULL',
        'string' => 'varchar(255) NOT NULL',
        'string_null'=>'varchar(255) NULL',
        'uri'=>'varchar(1024) NOT NULL',
        'uri_null'=>'varchar(1024) NULL',
        'text' => 'text',
        'integer' => 'int(11)',
        'float' => 'float',
        'decimal' => 'decimal',
        'datetime' => 'bigint(20) unsigned NOT NULL',
        'datetime_null' => 'bigint(20) unsigned NULL',
        'timestamp' => 'timestamp',
        'time' => 'time',
        'date' => 'date',
        'binary' => 'blob',
        'boolean' => "bit(1) NOT NULL DEFAULT b'0'",
        'money' => 'decimal(19,4)',
    );
}

/*
CREATE TABLE `user` (
  `UserID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `GUID` varchar(40) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `DisplayName` varchar(150) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Locked` bit(1) NOT NULL DEFAULT b'0',
  `StartDate` bigint(20) unsigned NOT NULL,
  `EndDate` bigint(20) unsigned NOT NULL,
  `LoginCount` bigint(20) unsigned NOT NULL,
  `LastLoginDate` bigint(20) unsigned DEFAULT NULL,
  `Anonymous` bit(1) NOT NULL DEFAULT b'0',
  `CreatedDate` bigint(20) unsigned NOT NULL,
  `CreatedBy` varchar(40) NOT NULL,
  `ModifiedDate` bigint(20) unsigned NOT NULL,
  `ModifiedBy` varchar(40) NOT NULL,
  `Rowversion` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `guid` (`GUID`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8$$
 */