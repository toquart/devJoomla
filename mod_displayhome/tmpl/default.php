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

 use Joomla\Module\Displayhome\Site\Helper\DisplayhomeHelper;

if(!$list){
   return;
}

 ?>


 <?php 
    foreach($list as $item):
 ?>
      <div class="containerHome">
         <a href="https://www.staderennaisathle.fr<?php echo $item->link;?>"><img class="img-vign" src="<?php echo json_decode($item->images)->image_intro; ?>"/>
         <div class="bottom-left">
            <h1 class="titleHead"><? echo $item->title;?></h1>
            <hr class="redBar" style="width: 5%; border-top: 2px solid #CC000C; opacity: 1;">
            <span class="mod-articles-category-date"><p style="margin-bottom: 0;"><? echo JHtml::_('date', $item->publish_up, JText::_('d F Y'));?></p><span>
         </div>
         </a>
      </div>
 <?php
      break;
    endforeach;
 ?>