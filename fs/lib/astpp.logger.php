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

class logger {

    var $fp;
    var $config;

    function logger($lib) {
        $this->config = $lib->config;
	    $this->config['log_path'] = "/var/log/astpp/";
        if ($this->config['debug'] == '0')
            $this->fp = fopen($this->config['log_path'] . 'astpp.log', 'a');
    }

    function log($log) {
        if ($this->config['debug'] == '0') {
            if (is_array($log))
                fwrite($this->fp, "[" . date('Y-m-d H:i:s') . "] " . print_r($log, TRUE));
            else
                fwrite($this->fp, "[" . date('Y-m-d H:i:s') . "] " . $log . "\n");
        }
    }

    function close() {
        if ($this->config['debug'] == '0')
            fclose($this->fp);
    }

}

?>
