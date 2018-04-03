<?php /* COMMON FUNCTIONS */

/* File Index

-- Generic
(array/bool) mysql_get($query)

*/

session_start();

//Given a MySQL query, will fetch results from the Alaska DB (Update for changes)
function mysql_get($query)
{
	//Login/DB information
    $server = "localhost";
	$login  = "secchi";//"mainevlm_dbuser2";
	$pass   = 'AsAp4U8u';//wxqsLAxqRHzdbjxV7ncLmqZ8";
	# $dbname = "mainevlm_recertify";
	$dbname = "mainevlm_secchi2";

    //Connect and Select DB
	mysql_connect($server,$login,$pass);
	mysql_select_db($dbname);

	//Get and return results
	$results = mysql_query($query);

	mysql_close();
	return $results;
}

