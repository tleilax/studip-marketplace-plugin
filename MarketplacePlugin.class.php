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
// this plugin is prohibited. !!
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

require_once 'includes/config.inc.php';
require_once 'classes/CryptMP.class.php';

class MarketplacePlugin extends StudipPlugin implements StandardPlugin
{
    function getTabNavigation($course_id)
    {
        $navigation = new Navigation('Marktplatz', PluginEngine::getURL($this, array(), "show"));
        return array('marketplace' => $navigation);
    }

    /**
     * Return a navigation object representing this plugin in the
     * course overview table or return NULL if you want to display
     * no icon for this plugin (or course). The navigation object’s
     * title will not be shown, only the image (and its associated
     * attributes like ’title’) and the URL are actually used.
     */
    function getIconNavigation($course_id, $last_visit, $user_id)
    {
        return null;
    }

    /**
     * Return a template (an instance of the Flexi_Template class)
     * to be rendered on the course summary page. Return NULL to
     * render nothing for this plugin.
     */
    function getInfoTemplate($course_id)
    {
        return null;
    }

    /** 
     * return a list of ContentElement-objects, conatinging  
     * everything new in this module 
     * 
     * @param  string   $course_id   the course-id to get the new stuff for 
     * @param  int      $last_visit  when was the last time the user visited this module 
     * @param  string   $user_id     the user to get the notifcation-objects for 
     * 
     * @return array an array of ContentElement-objects 
     */
    function getNotificationObjects($course_id, $since, $user_id)
    {
        return array();
    }

    /**
     * Display the plugin view template.
     */
    function show_action()
    {
        $GLOBALS['CURRENT_PAGE'] = 'Marktplatz';
        Navigation::activateItem('/course/marketplace');

        $userinfo = array(
            'first_name' => $GLOBALS['user']->Vorname,
            'last_name'  => $GLOBALS['user']->Nachname,
            'user_name'  => $GLOBALS['user']->username,
            'email'      => $GLOBALS['user']->Email,
        );
        $cryptinfo = array_map('CryptMP::encryptPublic', $userinfo);
        $cryptinfo = array_map('base64_encode', $cryptinfo);
        $info = base64_encode(serialize($cryptinfo));

        $factory = new Flexi_TemplateFactory($this->getPluginPath() . '/templates');

        $template = $factory->open('start');
        $template->set_layout($GLOBALS['template_factory']->open('layouts/base'));

        $template->uri              = MARKETPLACE_URI . '?dispatch=loginfromdev';
        $template->cryptloginkey    = base64_encode(CryptMP::encryptPublic(MARKETPLACE_REMOTE_LOGIN_KEY));
        $template->cryptinformation = $info;

        echo $template->render();
    }
}

