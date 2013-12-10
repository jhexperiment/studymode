<?php
/**
 * abstract class Reader
 * 
 * Abstract class to provide template for sub classes that reads files.
 * 
 */
abstract class Reader {
  abstract public function __construct($sUrl);
  abstract public function fnGetData(&$aData);
}

/**
 * class CSVReader
 * 
 * Class to read and retrieve data from CSV files.
 * 
 */
class CSVReader extends Reader {
  public $sUrl;
  /**
   * __construct()
   * 
   * Constructor
   * 
   * @param $sUrl String containing path of file to read.
   * 
   */
  public function __construct($sUrl) {
    $this->sUrl = $sUrl;
  }
  
  /**
   * fnGetData()
   * 
   * Function to read a CSV file and popuplate array with data.
   * 
   * @param &$aData Array Array to place data into.
   * 
   * @return Array Array containing data from file.
   */
  public function fnGetData(&$aData) {
    $aData = array();
    $aLineList = file($this->sUrl);
    foreach ($aLineList as $sLine) {
      $aData[] = explode(',', str_replace("\r\n", '', $sLine));
    }
    
    return $aData;
  }
}

/**
 * class NewFormatReader
 * 
 * Class to read and retrieve data from JSON files.
 * 
 */
class JsonReader extends Reader {
  public $sUrl;
  /**
   * __construct()
   * 
   * Constructor
   * 
   * @param $sUrl String containing path of file to read.
   * 
   */
  public function __construct($sUrl) {
    $this->sUrl = $sUrl;
    return $this;
  }

  /**
   * fnGetData()
   * 
   * Function to read a JSON data file and popuplate array with data.
   * 
   * @param &$aData Array Array to place data into.
   * 
   * @return Array Array containing data from file.
   */
  public function fnGetData(&$aData) {
    $aData = json_decode(file_get_contents($this->sUrl));
    
    return $aData;
  }
}