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
// Copyright (C) 2012 Jan Hendrik Willms <tleilax+studip@gmail.com>
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

require 'includes/config.inc.php';
require '../../../../cli/studip_cli_env.inc.php';

# requiring nusoap
require_once 'vendor/nusoap/nusoap.php';
require_once 'vendor/nusoap/class.delegating_soap_server.php';
require_once 'vendor/nusoap/class.soap_server_delegate.php';

# requiring soap_server_delegate
require_once 'vendor/studip_ws/studip_ws.php';
require_once 'vendor/studip_ws/soap_dispatcher.php';

# requiring all the webservices
require_once dirname(__FILE__).'/webservice/marketplace_webservice.php';

$delegate = new Studip_Ws_SoapDispatcher('MarketplaceService');
$server   = new DelegatingSoapServer($delegate);

# creating WSDL
$namespace = 'urn:studip_wsd';
$server->configureWSDL('Stud.IP Marketplace Webservice', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

# register operations
$delegate->register_operations($server);

# start server
$server->service($HTTP_RAW_POST_DATA ?: '');
