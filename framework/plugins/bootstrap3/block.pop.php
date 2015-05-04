<?php

##################################################
#
# Copyright (c) 2004-2015 OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

/**
 * Smarty plugin
 * @package Smarty-Plugins
 * @subpackage Block
 */

/**
 * Smarty {pop} block plugin
 *
 * Type:     block<br>
 * Name:     pop<br>
 * Purpose:  Set up a pop block
 *
 * @param array $params based on expJavascript::panel()
 *          'id' to differentiate popups
 *          'width' width of popup, defaults to '300px'
 *          'type' id type of popup, defaults to 'info', also 'error' & 'alert'
 *          'buttons' text string of 2 button names separated by ':'
 *          'title' title of popup
 *          'close' should popup have a close button (x), defaults to true
 *          'trigger' what object to base event trigger on, defaults to 'selfpop' which displays when popup is ready
 *          'on' what 'event' to display popup, defaults to 'load', or 'click' if 'trigger' is set
 *          'onnogo' what url to browse to when the 'no' button is selected
 *          'onyesgo' what url to browse to when the 'yes' button is selected
 *          'fade' seconds duration of popup 'fade' in/out, defaults to false
 *          'modal' should the popup be 'modal', defaults to true
 *          'draggable' should the popup be 'draggable', defaults to false
 *
 * @param $content
 * @param \Smarty $smarty
 * @param $repeat
 */
function smarty_block_pop($params,$content,&$smarty, &$repeat) {
	if($content){
        $content = str_replace("\r\n", '', trim($content));
        echo '<a href="#" id="' . $params['id'] . '">' . $params['text'] . '</a>';
        if (isset($params['type'])) {
            if ($params['type'] == 'warning') {
                $type = 'BootstrapDialog.TYPE_WARNING';
            } elseif ($params['type'] == 'danger') {
                $type = 'BootstrapDialog.TYPE_DANGER';
            }
        } else {
            $type = 'BootstrapDialog.TYPE_INFO';
        }
        $script = "
            $(document).ready(function(){
                $('#".$params['id']."').click(function() {
                    BootstrapDialog.show({
                        size: BootstrapDialog.SIZE_WIDE,
                        type: ".$type.",
                        title: '".$params['title']."',
                        message: '".$content."',
                        buttons: [{
                            label: '".$params['buttons']."',
                            action: function(dialogRef){
                                dialogRef.close();
                            }
                        }]
                    });
                });
            });
        ";
        expJavascript::pushToFoot(array(
            "unique"=>'pop-'.$params['name'],
            "bootstrap"=>'modal,transition,tab',
            "jquery"=>"bootstrap-dialog",
            "content"=>$script,
         ));

	}
}

?>

