<?php
class FanWall {
    private $appId;
    private $dao;

    public function  __construct($appId) {
        $this->appId = $appId;
        $this->dao = new Dao();
    }
/**
 * Returns the last comments in a array
 *
 * @param Integer $firstComment  Starting comment. If null starts from 0
 * @param Integer $limit    Number of comments to be returned
 *
 * @return Array            Returns an array with comments
 */
    public function getMessages ($limit, $location = NULL, $firstComment = NULL) {
        $whereClause = "";

        if ($location != NULL) {
            $parts = explode(';', $location);
            $lat = $parts[0];
            $lon = $parts[1];

            $orderBy = "ORDER BY CalculateDistance(".
                            "$lat,".
                            "$lon," .
                            "location" .
                        ") ASC, id DESC";
            if ($firstComment != NULL) {
                $limit = "$firstComment,$limit";
            }
        } else {
            if ($firstComment != NULL) {
                $whereClause = "AND id <= $firstComment";
            }
            $orderBy = "ORDER BY comment_id DESC";
        }

        $sql = "SELECT comment_id,user_name,user_type,timestamp,comment,image_uri,location,image_attachment, image_attachment_thumb, reply_to,reply_count FROM fan_wall, comments WHERE fan_wall.comment_id=comments.id AND fan_wall.app_id = {$this->appId} $whereClause $orderBy LIMIT $limit";
        //echo $sql;
        $commentArr = $this->dao->toArray($sql, MYSQL_ASSOC);
        return $this->_encodeComment($commentArr);
    }


    private function _encodeComment($commentArr) {
        $rtnArr = array();

        $cnt = 0;
        foreach ($commentArr as $comment) {
            $image = null;
            $image->uri = $comment['image_uri']!=""?$comment['image_uri']:"";
            $image->thumb_uri = "";

            $imageAttachment = new stdClass();
            $imageAttachment->uri = $comment['image_attachment']!=""?$comment['image_attachment']:"";
            $imageAttachment->thumb_uri = $comment['image_attachment_thumb']!=""?$comment['image_attachment_thumb']:"";;

            $rtnArr[$cnt]->comment_id = $comment['comment_id'];
            $rtnArr[$cnt]->user_name = $comment['user_name'];
            $rtnArr[$cnt]->user_type = $comment['user_type'];
            $rtnArr[$cnt]->timestamp = $comment['timestamp'];
            $rtnArr[$cnt]->comment = $comment['comment'];
            $rtnArr[$cnt]->image = $image;
            $rtnArr[$cnt]->location = $comment['location'];
            $rtnArr[$cnt]->image_attachment = $comment['image_attachment']!=''?$imageAttachment:null;
            $rtnArr[$cnt]->reply_to = $comment['reply_to']!=''?$comment['reply_to']:"";
            $rtnArr[$cnt]->reply_count = $comment['reply_count']!=''?$comment['reply_count']:"0";
            $cnt++;
        }

        return $rtnArr;
    }

/**
 * Returns the details of a comment
 *
 * @param Integer $commentId    Id of the comment that needs data
 *
 * @return Array            Returns an array with comments
 */
    public function getComment ($commentId) {
        $sql = "SELECT id as comment_id,user_name,user_type,timestamp,comment,image_uri,location,image_attachment,image_attachment_thumb, reply_to,reply_count FROM comments WHERE id = $commentId LIMIT 1";
        $commentArr = $this->dao->toArray($sql, MYSQL_ASSOC);

        return $this->_encodeComment($commentArr);
    }
    
/**
 * Returns all replys to a comment
 *
 * @param Integer $parentId  Parent comment
 * @param Integer $limit     Number of comments to be returned. If null all will be returned
 *
 * @return Array             Returns an array with comments
 */
    public function getReplys ($parentId, $limit=NULL) {

        if ($limit != NULL) {
            $limitText = "LIMIT $limit";
        } else {
            $limitText = '';
        }

        $sql = "SELECT id AS comment_id,user_name,user_type,timestamp,comment,image_uri,location,image_attachment,image_attachment_thumb,reply_to,reply_count FROM comments WHERE reply_to = $parentId $limitText";
        $commentArr = $this->dao->toArray($sql, MYSQL_ASSOC);

        return $this->_encodeComment($commentArr);
    }

/**
 * Returns the last comments in a array
 *
 * @param   Object $comment Comment object
 * @return  BOOL            Returns insert id on success and false on faliure
 */
    private function _addComment($commentObj) {
        $username = trim($commentObj->user_name);
        if ($username=='') {
            return FALSE;
        }
        $type = trim($commentObj->user_type);
        if ($type=='') {
            return FALSE;
        }
        $text = mysql_real_escape_string(trim($commentObj->comment));
        if ($text=='') {
            return FALSE;
        }

        $imageUri = isset($commentObj->image->uri)?trim($commentObj->image->uri):'';
        $location = trim($commentObj->location);
        $replyTo = isset($commentObj->reply_to)?"'".trim($commentObj->reply_to)."'":'NULL';
        $replyCount = isset($commentObj->reply_count)?"'".trim($commentObj->reply_count)."'":'0';

        $sql = "INSERT INTO `comments`(".
                    "`user_name` ,".
                    "`user_type` ,".
                    "`timestamp` ,".
                    "`comment` ,".
                    "`image_uri` ,".
                    "`location` ,".
                    "`reply_to` ,".
                    "`reply_count`,".
                    "`image_attachment`,".
                    "`image_attachment_thumb`".
               ") VALUES (".
                    "'$username',".
                    "'$type',".
                    "CURRENT_TIMESTAMP ,".
                    "'$text',".
                    "'$imageUri' ,".
                    "'$location',".
                    "$replyTo,".
                    "$replyCount,".
                    "NULL,".
                    "NULL".
                ")";

        try {
            $this->dao->query($sql);
            
            if (mysql_affected_rows()==0) {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return FALSE;
        }

        $insertId = mysql_insert_id();
        if(isset($commentObj->image_attachment)) {
            if(file_exists($commentObj->image_attachment)) {
                $this->_saveImageAttachment($commentObj->image_attachment, $insertId);

                $imageAttachmentURL = "http://192.168.0.100/phizuu_web/cms/apps/api1/images/$insertId.jpg";
                $imageAttachmentThumbURL = "http://192.168.0.100/phizuu_web/cms/apps/api1/images/".$insertId."_thumb.jpg";
                $sql = "UPDATE comments SET image_attachment = '$imageAttachmentURL',image_attachment_thumb = '$imageAttachmentThumbURL' WHERE id = $insertId";

                try {
                    $this->dao->query($sql);

                    if (mysql_affected_rows()==0) {
                        return FALSE;
                    }
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                    return FALSE;
                }

            }
        }

        return $insertId;
    }

    public function _saveImageAttachment($fileName, $id) {
        $image = imagecreatefromjpeg($fileName);

        $oWidth = imagesx($image);
        $oHeight = imagesy($image);

        $imageHeight = 100;
        $imageWidth = 100;

        $path = "images/".$id."_thumb.jpg";

        $rW = $imageWidth;
        $rH = ($oHeight/$oWidth)*$rW;

        $newImage = imagecreatetruecolor($imageWidth, $imageHeight);
        //echo $rH;
        if($rH>=$imageHeight) {
            $extraHeight = $rH - $imageHeight;

            $top = ($extraHeight/2)*($oWidth/$imageWidth);
            $src_h = $imageHeight * ($oWidth/$imageWidth);

            imagecopyresampled($newImage, $image, 0, 0, 0, $top, $imageWidth, $imageHeight, $oWidth, $src_h);
        } else {
            $rH = $imageHeight;
            $rW = ($oWidth/$oHeight) * $rH;

            $extraWidth = $rW - $imageWidth;

            $left = ($extraWidth/2)*($oHeight/$imageHeight);
            $src_w = $imageWidth * ($oHeight/$imageHeight);

            imagecopyresampled($newImage, $image, 0, 0, $left, 0, $imageWidth, $imageHeight, $src_w, $oHeight);
        }

        imagejpeg($newImage,$path,80);

        copy($fileName,"images/$id.jpg");
        unlink($fileName);
    }

/**
 * Adds a message
 *
 * @param   Object $commentObj  Comment object
 * @return  BOOL                Returns insert id on success and FALSE on faliure
 */
    public function addMessage($commentObj) {
        unset($commentObj->reply_to);
        $commentObj->reply_count = 0;

        $commentId = $this->_addComment($commentObj);

        if ($commentId===FALSE) {
            return FALSE;
        } else {
            $appId = $this->appId;
            $sql = "INSERT INTO `fan_wall` (".
                        "`app_id` ,".
                        "`comment_id`".
                    ")".
                    "VALUES (".
                        "'$appId',".
                        "'$commentId'".
                    ");";

            try {
                $this->dao->query($sql);
                if (mysql_affected_rows()==0) {
                    return FALSE;
                }
            } catch (Exception $exc) {
                return FALSE;
            }
        }

        return $commentId;
    }


/**
 * Adds a reply
 *
 * @param   Object $commentObj  Comment object
 * @return  BOOL                Returns insert id on success and FALSE on faliure
 */
    public function addReply($commentObj, $parent) {
        unset($commentObj->reply_count);
        $commentObj->reply_to = $parent;

        $commentId = $this->_addComment($commentObj);

        $sql = "UPDATE comments SET reply_count = reply_count+1 WHERE id=$parent";
        try {
            $this->dao->query($sql);
            if (mysql_affected_rows()==0) {
                return FALSE;
            }
        } catch (Exception $exc) {
            return FALSE;
        }
        return $commentId;
    }
}
?>
