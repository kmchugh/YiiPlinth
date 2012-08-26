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
        'pk' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
        'title'=>'varchar(150) NOT NULL',
        'title_null'=>'varchar(150) NULL',
        'description'=>'varchar(500) NULL',
        'string' => 'varchar(255)',
        'guid'=>'varchar(40) NOT NULL',
        'guid_null'=>'varchar(40) NULL',
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
        'boolean' => "bit(1) NOT NULL default b'0'",
        'boolean_null' => "bit(1) NULL",
        'money' => 'decimal(19,4)',
    );
}