<?php
/**
 * @author Jan-Hendrik Willms <tleilax+studip@gmail.com>
 * @author Jan Kulmann <jankul@zmml.uni-bremen.de>
 *
 * Authentication plugin for Stud.IP marketplace
 */

// +---------------------------------------------------------------------------+
// This file is NOT part of Stud.IP
// !! IMPORTANT: The redistribution of any key (soap and ssl public key) inside
// this plugin ist prohibited. !!
// Copyright (C) 2011 Jan Kulmann <jankul@zmml.uni-bremen.de>
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

class CryptMP
{
    public static function encryptPublic($txt)
    {
        $pub_key = file_get_contents(dirname(__FILE__) . '/../ssl/cert.crt');

        openssl_get_publickey($pub_key);
        openssl_public_encrypt($txt, $encrypted, $pub_key);

        return $encrypted;
    }

    public static function decryptPublic($txt)
    {
        $pub_key = file_get_contents(dirname(__FILE__) . '/../ssl/cert.crt');

        openssl_get_publickey($pub_key);
        openssl_public_decrypt($txt, $decrypted, $pub_key);

        return $decrypted;
    }
}
