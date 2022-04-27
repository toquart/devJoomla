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

 namespace Joomla\Module\Displayhome\Site\Helper;

 defined('_JEXEC') or die;

 use Joomla\CMS\Access\Access;
 use Joomla\CMS\Component\ComponentHelper;
 use Joomla\CMS\Factory;
 use Joomla\CMS\Router\Route;
 use Joomla\Component\Content\Site\Helper\RouteHelper;
 use Joomla\Component\Content\Site\Model\ArticlesModel;
 use Joomla\Registry\Registry;
 use Joomla\Utilities\ArrayHelper;

 // Added by @andy
#use Joomla\CMS\Date\Date;
#use Joomla\CMS\Filesystem\File;
//use Joomla\CMS\Filesystem\Folder; // doesn't work right now 20-12-2019
#use Joomla\CMS\Uri\Uri;
#use Joomla\CMS\Image\Image;

\JLoader::register('ContentHelperRoute', JPATH_SITE . 'components/com_content/helpers/route.php');

abstract class DisplayhomeHelper{
    /**
     * @param Registry
     * @param ArticlesModel
     * 
     * @return mixed
     */

     public static function getList(Registry $params, ArticlesModel $model){
         //Get the dbo
		$db = Factory::getDbo();
		$user = Factory::getUser();

        // Set application parameters in model
		$app       = Factory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		$model->setState('list.start', 0);
		$model->setState('filter.published', 1);

        // Access filter
		$access     = !ComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = Access::getAuthorisedViewLevels(Factory::getUser()->get('id'));
		$model->setState('filter.access', $access);

        // Category filter
		$model->setState('filter.category_id', $params->get('catid', array()));
		$model->setState('filter.tag', $params->get('filter_tag', array()));

        $items = $model->getItems();

        foreach ($items as &$item)
		{
			$item->slug    = $item->id . ':' . $item->alias;

			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link = Route::_(\ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
			}
			else
			{
				$item->link = Route::_('index.php?option=com_users&view=login');
			}
		}

        return $items;
     }
}