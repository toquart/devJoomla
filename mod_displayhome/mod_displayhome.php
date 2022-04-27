<?php
/**
 * @package Joomla.Site
 * @subpackage mod_displayhome
 * 
 * @copyright   Copyright (C) 2021 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * @author Adrien Beaugendre 
 */

 defined('_JEXEC') or die;

 use Joomla\CMS\Helper\ModuleHelper;
 use Joomla\Module\Displayhome\Site\Helper\DisplayhomeHelper;
 use Joomla\CMS\Factory;

 $document = JFactory::getDocument();

 $options = array("version" => "auto");
 $document->addStyleSheet(JURI::root() . "modules/mod_displayhome/tmpl/default/style.css", $options);

 $model = $app->bootComponent('com_content')->getMVCFactory()->createModel('Articles', 'Site', ['ignore_request' => true]);
 $list = DisplayhomeHelper::getList($params, $model);

 require ModuleHelper::getLayoutPath('mod_displayhome', $params->get('layout', 'default'));
