<?php
/**
 * @package     HomePage
 * @subpackage  mod_homepage
 *
 * @copyright   Copyright (C) 2021 Adrien Beaugendre. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.ht
 */

 namespace Toq\Module\Homepage\Site\Helper;

 \defined('_JEXEC') or die;

 use Joomla\CMS\Access;
 use Joomla\CMS\Component\ComponentHelper;
 use Joomla\CMS\Factory;
 use Joomla\CMS\Router\Route;
 use Joomla\Component\Content\Site\Helper\RouteHelper;
 use Joomla\Component\Content\Site\Model\ArticlesModel;
 use Joomla\Registry\Registry;
 use Joomla\Utilities\ArrayHelper;

 abstract class HomepageHelper
 {
     /**
      * Retrieve a list of article
      *
      * @param   Registry       $params  The module parameters.
      * @param   ArticlesModel  $model   The model.
      *
      * @return  mixed
      *
      * @since   1.6
      */
     public static function getList(Registry $params, ArticlesModel $model)
     {
         // Get the Dbo and User object
         $db   = Factory::getDbo();
         $user = Factory::getUser();
 
         // Set application parameters in model
         $app       = Factory::getApplication();
         $appParams = $app->getParams();
         $model->setState('params', $appParams);
 
         $model->setState('list.start', 0);
         $model->setState('filter.published', 1);
 
         // Set the filters based on the module params
         $model->setState('list.limit', (int) $params->get('count', 5));
 
         // This module does not use tags data
         $model->setState('load_tags', false);
 
         // Access filter
         $access     = !ComponentHelper::getParams('com_content')->get('show_noauth');
         $authorised = Access::getAuthorisedViewLevels($user->get('id'));
         $model->setState('filter.access', $access);
 
         // Category filter
         $model->setState('filter.category_id', $params->get('catid', array()));
 
         // State filter
         $model->setState('filter.condition', 1);
 
         // User filter
         $userId = $user->get('id');
 
         switch ($params->get('user_id'))
         {
             case 'by_me':
                 $model->setState('filter.author_id', (int) $userId);
                 break;
             case 'not_me':
                 $model->setState('filter.author_id', $userId);
                 $model->setState('filter.author_id.include', false);
                 break;
 
             case 'created_by':
                 $model->setState('filter.author_id', $params->get('author', array()));
                 break;
 
             case '0':
                 break;
 
             default:
                 $model->setState('filter.author_id', (int) $params->get('user_id'));
                 break;
         }
 
         // Filter by language
         $model->setState('filter.language', $app->getLanguageFilter());
 
         // Featured switch
         $featured = $params->get('show_featured', '');
 
         if ($featured === '')
         {
             $model->setState('filter.featured', 'show');
         }
         elseif ($featured)
         {
             $model->setState('filter.featured', 'only');
         }
         else
         {
             $model->setState('filter.featured', 'hide');
         }
 
         // Set ordering
         $order_map = array(
             'm_dsc'  => 'a.modified DESC, a.created',
             'mc_dsc' => 'a.modified',
             'c_dsc'  => 'a.created',
             'p_dsc'  => 'a.publish_up',
             'random' => $db->getQuery(true)->rand(),
         );
 
         $ordering = ArrayHelper::getValue($order_map, $params->get('ordering'), 'a.publish_up');
         $dir      = 'DESC';
 
         $model->setState('list.ordering', $ordering);
         $model->setState('list.direction', $dir);
 
         $items = $model->getItems();
 
         foreach ($items as &$item)
         {
             $item->slug    = $item->id . ':' . $item->alias;
 
             if ($access || \in_array($item->access, $authorised))
             {
                 // We know that user has the privilege to view the article
                 $item->link = Route::_(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language));
             }
             else
             {
                 $item->link = Route::_('index.php?option=com_users&view=login');
             }
         }
 
         return $items;
     }
 }
 