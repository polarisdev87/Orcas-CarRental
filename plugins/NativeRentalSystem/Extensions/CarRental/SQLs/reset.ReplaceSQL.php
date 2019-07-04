<?php
/**
 * Extension replace sql when plugin is (re)enabled
 * @note        Fired every time when plugin is enabled, or enabled->disabled->enabled, etc.
 * @note2       MySQL 'REPLACE INTO' works like MySQL 'INSERT INTO', except that if there is a row
 *              with the same key you are trying to insert, it will be deleted on replace instead of giving you an error.
 * @note3       Supports [BLOG_ID] BB code
 * @package     NRS
 * @author      Kestutis Matuliauskas
 * @copyright   Kestutis Matuliauskas
 * @License     See Licensing/README_License.txt for copyright notices and details.
 */
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );

$arrReplaceSQL = array();
$arrExtensionReplaceSQL = array();

$arrExtensionReplaceSQL['settings'] = "(`conf_key`, `conf_value`, `blog_id`) VALUES
('conf_benefit_thumb_h', '81', '[BLOG_ID]'),
('conf_benefit_thumb_w', '71', '[BLOG_ID]'),
('conf_item_big_thumb_h', '225', '[BLOG_ID]'),
('conf_item_big_thumb_w', '360', '[BLOG_ID]'),
('conf_item_mini_thumb_h', '63', '[BLOG_ID]'),
('conf_item_mini_thumb_w', '100', '[BLOG_ID]'),
('conf_item_thumb_h', '150', '[BLOG_ID]'),
('conf_item_thumb_w', '240', '[BLOG_ID]'),
('conf_location_big_thumb_h', '225', '[BLOG_ID]'),
('conf_location_big_thumb_w', '360', '[BLOG_ID]'),
('conf_location_mini_thumb_h', '63', '[BLOG_ID]'),
('conf_location_mini_thumb_w', '100', '[BLOG_ID]'),
('conf_location_thumb_h', '179', '[BLOG_ID]'),
('conf_location_thumb_w', '179', '[BLOG_ID]'),
('conf_manufacturer_thumb_h', '179', '[BLOG_ID]'),
('conf_manufacturer_thumb_w', '179', '[BLOG_ID]')";