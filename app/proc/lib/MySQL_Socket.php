<?php
//  Get required units.
require_once 'Log.php';
require_once 'JSON_Socket.php';

/**
* @uses   Log
* @uses   JSON_Socket
*
* This class handles MySQL cridentaisl and connection creation.
*
* @category       DataStoreage
* @package        DataSockets
* @subpackage     Database
* @version        2.0
* @since          1.0
* @deprecated     ---
* */
class MySQL_Socket {

  static private $config;
  static protected $DB;

  /**
  * @method   Logs an exception for debug.
  * @param    Number, The error type id.
  * @param    String, The error message.
  * */
  private function Error($nr, $msg) {
    $Log = new Log('MySQL_Socket_Exceptions');
    $Log->Write(new Exception($nr." : ".$msg));
  }

  /**
  * @method   Reads and verifies the credentials before atempting to connect.
  * */
  protected function __construct() {
    //  Read the connection configuration.
    $JSON = new JSON_Socket();
    $raw_conf = $JSON->Read('MySQL_Config');
    //  Verify config is valid.
    if (!$raw_conf) {
      self::Error(1, 'MySQL_Config; File is missing.');
      self::$DB = false;
      return;
    } else if (!property_exists($raw_conf, 'creds')
      || !property_exists($raw_conf->creds, 'host')
      || !property_exists($raw_conf->creds, 'user')
      || !property_exists($raw_conf->creds, 'pw')
      || !property_exists($raw_conf->creds, 'db')
    ) {
      self::Error(2, 'MySQL_Config; Credential(s) are missing.');
      self::$DB = false;
      return;
    }
    //  Hold on to cridentials if valid.
    self::$config = $raw_conf;
    //  Set default charset if not specified.
    if (!property_exists(self::$congif, 'charset')
      || self::$config->charset === ''
    ) { self::$config->charset = 'utf8'; }
    // Create the connection and handle any exceptions localy.
    set_error_handler(function($nr, $msg) { self::Error($nr, $msg); });
    $DB = new mysqli(
      self::$config->credential->host, self::$config->credential->user,
      self::$config->credential->pw, self::$config->credential->db
    );
    restore_error_handler();
    //  Confirm no errors occurred or set connection to false.
    if ($DB->connect_error) {
      self::Error(
        $DB->error ? 3 : 4, $DB->error ? $DB->error : $DB->connect_error
      );
      self::$DB = false;
      return;
    }
    //  Set charset before holding on to the connection.
    $DB->set_charset(self::$config->charset);
    self::$DB = $DB;
  }

}

?>
