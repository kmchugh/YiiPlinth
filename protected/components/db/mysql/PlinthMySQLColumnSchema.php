<?php
class PlinthMySQLColumnSchema extends CMysqlColumnSchema
{
	/**
	 * Creates a table column.
	 * @param array $toColumn column metadata
	 * @return CDbColumnSchema normalized column metadata
	 */
	protected function createColumn($toColumn)
	{
		$loColumn=new CMysqlColumnSchema;
		$loColumn->name=$toColumn['Field'];
		$loColumn->rawName=$this->quoteColumnName($loColumn->name);
		$loColumn->allowNull=$toColumn['Null']==='YES';
		$loColumn->isPrimaryKey=strpos($toColumn['Key'],'PRI')!==false;
		$loColumn->isForeignKey=false;
		$loColumn->init($toColumn['Type'],$toColumn['Default']);
		$loColumn->autoIncrement=strpos(strtolower($toColumn['Extra']),'auto_increment')!==false;

		return $loColumn;
	}

	/**
	 * Extracts the PHP type from DB type.
	 * @param string $tcDBType DB type
	 */
	protected function extractType($tcDBType)
	{
		if(strncmp($tcDBType,'enum',4)===0)
			$this->type='string';
		else if(strpos($tcDBType,'float')!==false || strpos($tcDBType,'double')!==false)
			$this->type='double';
		else if(strpos($tcDBType,'bool')!==false || preg_match("/(bit).+?/", $tcDBType))
			$this->type='boolean';
		else if(strpos($tcDBType,'int')===0 && strpos($tcDBType,'unsigned')===false || preg_match('/(tinyint|smallint|mediumint)/',$tcDBType))
			$this->type='integer';
		else
			$this->type='string';
	}
}
?>