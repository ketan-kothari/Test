<?php
###############################################################################
# ASTPP - Open Source VoIP Billing Solution
#
# Copyright (C) 2016 iNextrix Technologies Pvt. Ltd.
# Samir Doshi <samir.doshi@inextrix.com>
# ASTPP Version 3.0 and above
# License https://www.gnu.org/licenses/agpl-3.0.html
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as
# published by the Free Software Foundation, either version 3 of the
# License, or (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
# 
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
###############################################################################

define('ENVIRONMENT', 'development');
if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			error_reporting(E_ALL);
            error_reporting(E_ERROR|E_WARNING|E_PARSE);
		break;
	
		case 'testing':
		case 'production':
			error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}


/*$db->run("SET NAMES utf8");
$data = json_decode(file_get_contents("php://input"),true);

$myfile = fopen("/var/log/astpp/json_cdr/".$_GET['uuid']."json.cdr", "w") or die("Unable to open file!");
fwrite($myfile,$data);
fclose($myfile);

exit;*/

include ("lib/astpp.cdr.db.php");
include ("lib/astpp.logger.php");
include ("lib/astpp.lib.php");
include ("lib/astpp.constants.php");
include ("lib/astpp.cdr.php");

//Define db object
$db = new db();

//Get default configuration
$lib = new lib();
$config = $lib->get_configurations($db);

//Set default decimal points 
$decimal_points = ($config['decimal_points'] <= 0)?4:$config['decimal_points'];

//Define logger object
$logger = new logger($lib);

//Get Table Name

if(isset($_SERVER["CONTENT_TYPE"]) && $_SERVER["CONTENT_TYPE"] == "application/json") {

    $db->run("SET NAMES 'utf8'");
    $data = json_decode(file_get_contents("php://input"),true);
    //error_log(print_r($data,true));

    $data["tbl_suffix"] =  $config['tbl_suffix'];
    $logger->log(print_r($data,true));

    //[SD - 22-Jun-2017] Added if condition to avoid B leg cdr writting process as it was giving syntax error in postgres.
    if(!isset($data['variables']['originating_leg_uuid'])){
    	process_cdr($data,$db,$logger,$decimal_points);  
    }
}

//$db->cleanup();
?>
