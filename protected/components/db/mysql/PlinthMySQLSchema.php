<?php

/**
 * The PlinthDBSchema extends some of the rules and generations
 * from the CDbSchema class
 */
class PlinthMySQLSchema extends CMysqlSchema
{
    /**
     * @var array the abstract column types mapped to physical column types.
     * @since 1.1.6
     */
    public $columnTypes=array(
        'binary' => 'blob',
        'boolean' => "bit(1) NOT NULL DEFAULT b'0'",
        'code' => 'varchar(40) NOT NULL',
        'code_null' => 'varchar(40) NULL',
        'date' => 'date',
        'datetime' => 'bigint(20) unsigned NOT NULL',
        'datetime_null' => 'bigint(20) unsigned NULL',
        'decimal' => 'decimal',
        'description'=>'varchar(500) NULL',
        'guid'=> 'varchar(40) NOT NULL',
        'guild_null'=>'varchar(40) NULL',
        'float' => 'float',
        'id' => 'bigint(20) NOT NULL',
        'id_null' => 'bigint(20) NULL',
        'integer' => 'int(11)',
        'long_string' => 'varchar(1024) NOT NULL',
        'long_string_null'=>'varchar(1024) NULL',
        'money' => 'decimal(19,4)',
        'pk' => 'bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
        'string' => 'varchar(255) NOT NULL',
        'string_null'=>'varchar(255) NULL',
        'text' => 'text',
        'time' => 'time',
        'timestamp' => 'timestamp',
        'title' => 'varchar(150) NOT NULL',
        'uri'=>'varchar(1024) NOT NULL',
        'uri_null'=>'varchar(1024) NULL',
    );
}