<?php


Class MachineLists {
    /*--------------------------------------------------------------------------
		properties
	--------------------------------------------------------------------------*/
	public $tablename = "machine_list";

    /*--------------------------------------------------------------------------
		constructor
	--------------------------------------------------------------------------*/
	public function __construct($host, $username, $password, $databasename) {
		$this->_mysqli = new mysqli($host, $username, $password, $databasename);
		$this->databasename = $databasename;
		$this->_mysqli->query("SET NAMES utf8");
		$this->defineProperties();
	}

	/*--------------------------------------------------------------------------
		searchList
	--------------------------------------------------------------------------*/
    public function searchList() {
		$sql = "select * from ".$this->tablename;
		$sql .= " order by number";
		return $this->search($sql);
	}
}