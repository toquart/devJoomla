<?php
/**
 * @package    Toq.Module
 * @subpackage  mod_homepage
 *
 * @copyright   Copyright (C) 2021 Adrien Beaugendre. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Toq\Module\Homepage\Site\Helper\HomepageHelper;

$model = $app->bootComponent('com_content')->getMVCFactory()->createModel('Articles', 'Site', ['ignore_request' => true]);
$list = HomepageHelper::getList($params, $model);

require ModuleHelper::getLayoutPath('mod_homepage', $params->get('layout', 'default'));
