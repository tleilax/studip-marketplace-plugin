<?php

/**
 * @author Jan-Hendrik Willms <tleilax+studip@gmail.com>
 * @author Jan Kulmann <jankul@zmml.uni-bremen.de>
 *
 * Authentication plugin for Stud.IP marketplace
 *
 */

// +---------------------------------------------------------------------------+
// This file is NOT part of Stud.IP
// !! IMPORTANT: The redistribution of any key (soap and ssl public key) inside
// this plugin ist prohibited. !!
// Copyright (C) 2011 Jan Kulmann <jankul@zmml.uni-bremen.de>
// Copyright (C) 2012 Jan-Hendrik Willms <tleilax+studip@gmail.com>
// +---------------------------------------------------------------------------+
// This program is free software under these terms: you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation, either version 2
// of the License, or any later version BUT WITHOUT ANY KEY INSIDE.
// +---------------------------------------------------------------------------+
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// +---------------------------------------------------------------------------+

require_once dirname(__FILE__) . '/../classes/CryptMP.class.php';

/**
 * Kind of Mock for Stud.IP Permission Class.
 *
 * @package   studip
 * @package   webservice
 *
 * @author    mlunzena
 * @copyright (c) Authors
 */
class MockPermissionMP extends Seminar_Perm {
  function have_perm($perm) { return TRUE; }
  function is_fak_admin()   { return TRUE; }
}


class MarketplaceService extends Studip_Ws_Service
{
    function __construct()
    {
        $this->add_api_method('get_user_by_user_name',
            array('', ''),
            'string',
            'finds a user by username'
        );
        $this->add_api_method('check_user_credentials',
            array('', '', ''),
            true,
            'checks if given username and password match'
        );
    }

    /**
     * This method is called before every other service method and tries to
     * authenticate an incoming request using the first argument as an so
     * called "api key". If the "api key" matches a valid one, the request will
     * be authorized, otherwise a fault is sent back to the caller.
     *
     * @param string the function's name.
     * @param array an array of arguments that will be delivered to the function.
     *
     * @return mixed if this method returns a "soap_fault" or "FALSE", further
     *               processing will be aborted and a "soap_fault" delivered.
     */
    function before_filter($name, &$args)
    {
        # get api_key
        $api_key = current($args);
        if ($api_key != SOAP_API_KEY) {
            return new Studip_Ws_Fault('Could not authenticate client.');
        }
    }


    /**
     * Searches for a user using the user's user name.
     *
     * @param string the api key.
     * @param string the user's username.
     *
     * @return mixed the found User struct or a fault if the user could not be
     *               found.
     */
    function get_user_by_user_name_action($api_key, $user_name)
    {
        $user_name = CryptMP::decryptPublic(base64_decode($user_name));
        $user =& User::findByUsername($user_name);

        if (!$user) {
            return new soap_fault('Server', '', 'No such user.');
        }

        $info = array(
            'first_name' => $user->Vorname,
            'last_name'  => $user->Nachname,
            'email'      => $user->Email,
        );
        $info = array_map('CryptMP::encryptPublic', $info);
        $info = array_map('base64_encode', $info);

        return base64_encode(serialize($info));
    }

    /**
     * check authentication for a user.
     *
     * @param string the api key.
     * @param string the user's username.
     * @param string the user's username.
     *
     * @return boolean returns TRUE if authentication was successful or a fault
     *                 otherwise.
     */
    function check_user_credentials_action($api_key, $username, $password)
    {
        $username = CryptMP::decryptPublic(base64_decode($username));
        $password = CryptMP::decryptPublic(base64_decode($password));

        list($user_id, $error_msg, $is_new_user) = array_values(StudipAuthAbstract::CheckAuthentication($username, $password));
        if ($user_id === false) {
          return new Studip_Ws_Fault(strip_tags($error_msg));
        }
        return true;
    }
}
