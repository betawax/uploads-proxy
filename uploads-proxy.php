<?php

/**
 * Plugin Name: Uploads Proxy
 * Plugin URI: https://github.com/betawax/uploads-proxy
 * Description: Proxy uploads from the production site.
 * Version: 1.0.1
 * Author: Holger Weis
 * Author URI: http://betawax.io
 * License: MIT
 */

require_once ABSPATH.'wp-admin/includes/file.php';

/**
 * Get uploads from the production site and store them
 * in the local filesystem if they don't already exist.
 *
 * @return  void
 */
function uploads_proxy()
{
	global $wp_filesystem; WP_Filesystem();
	
	// The relative request path
	$requestPath = $_SERVER['REQUEST_URI'];
	
	// The relative uploads path
	$uploadsPath = str_replace(get_bloginfo('url'), '', wp_upload_dir()['baseurl']);
	
	// Check if a upload was requested
	if (strpos($requestPath, $uploadsPath) === 0)
	{
		// The absolute remote path to the upload
		$remotePath = UP_SITEURL.$requestPath;
		
		// Get the remote upload file
		$response = wp_remote_get($remotePath);
		
		// Check the response code
		if ($response['response']['code'] === 200)
		{
			// The file path relative to the uploads path to store the upload file to
			$relativeUploadFile = str_replace($uploadsPath, '', $_SERVER['REQUEST_URI']);
			
			// The absolute file path to store the upload file to
			$absoluteUploadFile = wp_upload_dir()['basedir'].$relativeUploadFile;
			
			// Make sure the upload directory exists
			wp_mkdir_p(pathinfo($absoluteUploadFile)['dirname']);
			
			if ($wp_filesystem->put_contents(urldecode($absoluteUploadFile), $response['body'], FS_CHMOD_FILE))
			{
				// Redirect to the stored upload
				wp_redirect($requestPath);
			}
		}
	}
}

// Only execute in an development or staging environment
if ((WP_ENV === 'development' || WP_ENV === 'staging') && UP_SITEURL)
{
	// Hook into the 404 page
	add_filter('404_template', 'uploads_proxy');
}
