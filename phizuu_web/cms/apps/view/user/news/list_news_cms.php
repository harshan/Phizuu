<?php

require_once("../../../config/config.php");
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once('../../../controller/helper.php');
require_once('../../../controller/news_controller.php');
require_once('../../../controller/pagination_controller.php');
require_once('../../../model/news_model.php');
require_once('../../../config/error_config.php');


$news = new News();
$count = 1;


$numrows = $news->listNewsAll($_SESSION['user_id']);
$news_list = $news->listNews($_SESSION['user_id'], 0, $numrows);

$response = '';

if (sizeof($news_list) > 0) {
    foreach ($news_list as $lst_news) {
        $response .= '<li id="id_' . $lst_news->id . '">
                    <div class="dragHandle"></div>
                    <div style="float:left;height:45px;width: 216px;">
                    <div class="title click" id="div1_' . $lst_news->id . '_' . $count . '" style="height:27px;overflow: hidden;line-height:16px">' . $lst_news->title . '</div>';

        if (array_key_exists($lst_news->id, $dataList)) {
            $itemExist = TRUE;
        }

        $response .='<div style=";padding-left: 4px;width: 216px;vertical-align: bottom;background-color: #d3d3d3;clear:both;height:21px" id="div_source">
              <span class="showViews" id="showViews_' . $lst_news->id . '"><img src="../../../images/icon_views.png" /><span style="padding: 0px 5px 2px 5px">';

        if (isset($itemExist) && $itemExist == TRUE):
            $response .= $dataList[$lst_news->id][3];
        else:
            $response .= "0";
        endif;

        $response.='</span></span>
             <span class="showViews" id="showLikes_' . $lst_news->id . '"><img src="../../../images/icon_like.png"/><span style="padding: 0 5px 2px 5px">';
        if (isset($itemExist) && $itemExist == TRUE):
            $response .= $dataList[$lst_news->id][0];
        else:
            $response .= '0';
        endif;
        $response.='</span></span>
            <span class="showViews" id="showShare_' . $lst_news->id . '"><img src="../../../images/icon_share.png"/><span style="padding: 0 5px 2px 5px">';
        if (isset($itemExist) && $itemExist == TRUE):
            $response .= $dataList[$lst_news->id][1];
        else:
            $response .= '0';
        endif;
        $response.='</span></span>
                                                    <span class="showViews" id="showComments_' . $lst_news->id . '"><img src="../../../images/icon_comment.png"/><span style="padding: 0 5px 2px 5px">';
        if (isset($itemExist) && $itemExist == TRUE):
            $response .= $dataList[$lst_news->id][2];
        else:
            $response .= '0';
        endif;
        $response.='</span></span><input type="hidden" id="commentsCount_' . $lst_news->id . '" value="';
        if (isset($itemExist) && $itemExist == TRUE):
            $response .= $dataList[$lst_news->id][2];
        else:
            $response .= '0';
        endif;
        $response.='                                 "/>
                                                    </div>
                                                <div id="viewToolTip' . $lst_news->id . '" class="div_tooltip"></div>
                                                <div id="tooltip_comment' . $lst_news->id . '" class="div_tooltip_comment" style="margin: -10px 0 0 115px;"></div>';

        $response .= '</div><div class="date" style="width:150px;"><input type="text" name="hid2_' . $count . '" id="hid2_' . $count . '" value="' . $lst_news->date . '"  maxlength="10" size="12" readonly /><img src="../../../images/cal.gif" id="btn2_' . $count . '" onclick="calendar(\'btn2_' . $count . '\',\'hid2_' . $count . '\',\'div4_' . $lst_news->id . '_' . $count . '\');" onmouseover="calendar(\'btn2_' . $count . '\',\'hid2_' . $count . '\',\'div4_' . $lst_news->id . '_' . $count . '\');" /></div>
               <div style="height:64px;float:left;background-color:#d3d3d3">
               <div class="description editable_textarea" style="height:41px;;line-height:16px" id="div3_' . $lst_news->id . '_' . $count . '">' . $lst_news->description . '</div>
                   
               </div>
               <div class="ttip" style="float:left">
               <div class="action" style="width:34px" id="delete_'.$lst_news->id.'"><img src="../../../images/cross.png" style="cursor: pointer" onclick="deleteItem(' . $lst_news->id . ')" /></div></div>
                   <div id="div_tooltip_common_'.$lst_news->id.'" class="div_tooltip_common">Delete</div>
    </li>';

        $count++;
        $itemExist = FALSE;
    }
}

echo $response;
?>