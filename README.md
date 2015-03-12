# Uploads Proxy

Uploads Proxy is a WordPress plugin that will automatically download any requested file located in the uploads directory of your production site to your development or staging environment.

**Note:** this plugin is currently only tested on WordPress sites built on the excellent [Bedrock](https://github.com/roots/bedrock) stack.

## How does it work?

The plugin hooks into the WordPress 404 page and listen for requests to upload files that doesn't exist in the local environment. If possible, the plugin will then download the files from your production site and store them locally, so this whole process is only executed once per file.

Please note that the plugin only executes if your environment (`WP_ENV`) is set to either `development` or `staging`. No harm is done to your production environment.

You can simply clear your local uploads directory at any time to restart the whole process.

## Requirements

- WordPress 3.9+ *
- [Bedrock](https://github.com/roots/bedrock)
- [PHP dotenv](https://github.com/vlucas/phpdotenv)
- PHP 5.4+

\* The plugin may also work with older versions of WordPress, however I haven't tested it.

## Usage

**1. Install and activate the plugin**

	composer require betawax/uploads-proxy

**2. Add a constant to your environment configuration(s)**

	define('UP_SITEURL', getenv('UP_SITEURL'));

You will want to add this to your `config/environments/development.php` and `config/environments/staging.php` files.

**3. Define the URL to your production site**

Add the following line to your local `.env` file:

	UP_SITEURL=http://example.com

**4. Profit?**

Open your local environment in your browser and give it some time to download the files on the first request.

## Credits

This plugin is inspired by the [Stage File Proxy](https://www.drupal.org/project/stage_file_proxy) Drupal module and basically does the same as the [Uploads by Proxy](https://wordpress.org/plugins/uploads-by-proxy/) WordPress plugin (which doesn't seem to work with Bedrock).

## License

Licensed under the MIT license.
