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