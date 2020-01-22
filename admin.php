<?php

if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}



/*
 * Register the plugin menu items.
 */
if (function_exists('XH_registerStandardPluginMenuItems')) {
    XH_registerStandardPluginMenuItems(true);
}

if (function_exists('pdf_gallery') 
    && XH_wantsPluginAdministration('pdf_gallery') 
    || isset($pdf_gallery) && $pdf_gallery == 'true')
{


    $o .= print_plugin_admin('off');

    switch ($admin) {

	    case '':
	        $o .= '<h1>PDF-Gallery</h1>';
    		$o .= '<p>Version 0.9.0</p>';
            $o .= '<p>Copyright 2019</p>';
    		$o .= '<p><a href="http://www.nibble-arts.org" target="_blank">Thomas Winkler</a></p>';
            $o .= '<p>Mit dem Plugin ist es möglich PDF-Dateien aus einem Verzeichnis zum Download anzubieten und automatisch JPG-Vorschaubilder der ersten Seite zu erstellen.</p>';

	        break;

        case 'plugin_main':

            $o .= '<a href="?pdf_gallery&admin=plugin_main&action=plugin_reset&normal">' . $plugin_tx['pdf_gallery']['reset_thumbs'] . '</a><br>';
            $o .= $plugin_tx['pdf_gallery']['thumbs_deleted_comment'];

            switch($action) {

                case 'plugin_reset':
                    $pdf_gallery = new pdf_gallery\Plugin_Pdf_Gallery($plugin_cf['pdf_gallery']);

                    if ($pdf_gallery->reset()) {
                        $o .= '<div class="xh_success">' . $plugin_tx['pdf_gallery']['thumbs_deleted'] . '</div>';
                    }
                    else {
                        $o .= '<div class="xh_warning">' . $plugin_tx['pdf_gallery']['thumbs_delete_error'] . '</div>';
                    }

                    break;

            }


            break;


        default:
     //        include_once(DATABASE_BASE."settings.php");

     //        $o .= database_settings($action, $admin, $plugin);
     //        break;

	    // default:
	        $o .= plugin_admin_common($action, $admin, $plugin);
            break;
    }

}

?>