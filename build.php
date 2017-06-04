#!/usr/bin/env php
<?php
echo "Wordpress core path [".getenv('WP_CORE_DIR')."]:";
define('DS', DIRECTORY_SEPARATOR);
$corePath = trim(fgets(STDIN));
$currentPath = dirname(__FILE__);
$publicPath = $currentPath .  DS . 'public';

function mkPath($path, $mode=0755) {
  if (!file_exists($path)) {
    mkdir($path, $mode);
  }
  chmod($path, $mode);
}

function mkLink($name) {
  global $corePath, $publicPath;
  if (!file_exists($publicPath . DS . $name)) {
    symlink($corePath . DS . $name, $publicPath . DS . $name);
  }
}


if (empty($corePath))
  $corePath = getenv('WP_CORE_DIR');
if (file_exists($corePath)) {
  if (!file_exists($corePath . DS . 'wp-config-sample.php'))
    die($corePath . ' is not a wordpress directory! Input the full path which contains wp-config-sample.php'. PHP_EOL);

  mkPath($publicPath);
  copy($corePath . DS. 'wp-config-sample.php' , $currentPath . DS .'wp-config-sample.php');
  mkLink('wp-admin');
  mkLink('wp-includes');
  mkLink('wp-login.php');
  mkLink('wp-settings.php');
  $base = basename(dirname(__FILE__));
  $htaccess = <<<EOT
SetEnv WP_ABSPATH $publicPath

#BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
EOT;
  $file = fopen($publicPath . DS . ".htaccess","w");
  fwrite($file, $htaccess);
  fclose($file);
  $file = fopen($publicPath . DS . "index.php","w");
  fwrite($file, <<<EOT
<?php
define('DS', DIRECTORY_SEPARATOR);
define('WP_USE_THEMES', true);
define('WP_CORE_DIR', '$corePath');
define('ABSPATH', '$publicPath' . DS);
require( WP_CORE_DIR . DS . 'wp-blog-header.php');
EOT
);
  fclose($file);
  //copy theme
  mkPath($publicPath . DS . 'wp-content');
  mkPath($publicPath . DS . 'wp-content'. DS . 'themes');
  exec('cp -r '. $corePath . DS . 'wp-content'. DS .'themes'. DS . 'twentyseventeen' . ' ' . $publicPath . DS . 'wp-content'.DS.'themes'.DS);
  mkPath($publicPath . DS . 'wp-content' . DS . 'uploads', 0766);

  mkLink('wp-content/languages');
  mkLink('wp-content/plugins');
} else {
  die($corePath. ' not exists! '. PHP_EOL);
}
