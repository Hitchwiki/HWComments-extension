<?php

class HWAddCommentApi extends ApiBase {
  public function execute() {
    global $wgUser;
    if (!$wgUser->isAllowed('edit')) {
      $this->dieUsage("You don't have permission to add comment", "permissiondenied");
    }

    $params = $this->extractRequestParams();
    $page_id = $params['pageid'];
    $user_id = $wgUser->getId();
    $commenttext = $params['commenttext'];
    $timestamp = wfTimestampNow();
    $pageObj = $this->getTitleOrPageId($params);

    // Exit with an error if pageid is not valid (eg. non-existent or deleted)
    $this->getTitleOrPageId($params);

    $dbw = wfGetDB( DB_MASTER );
    $dbw->insert(
      'hw_comments',
      array(
        'hw_user_id' => $user_id,
        'hw_page_id' => $page_id,
        'hw_commenttext' => $commenttext,
        'hw_timestamp' => $timestamp
      )
    );
    $comment_id = $dbw->insertId();

    // Get fresh comment count
    $res = $dbw->select(
      'hw_comments',
      array(
        'COUNT(*) AS count_comment'
      ),
      array(
        'hw_page_id' => $page_id
      )
    );
    $row = $res->fetchRow();
    $count = $row['count_comment'];

    // Update comment count cache
    $dbw->upsert(
      'hw_comments_count',
      array(
        'hw_page_id' => $page_id,
        'hw_comments_count' => $count
      ),
      array('hw_page_id'),
      array(
        'hw_comments_count' => $count
      )
    );

    $this->getResult()->addValue('query' , 'count', intval($count));
    $this->getResult()->addValue('query' , 'pageid', intval($page_id));
    $this->getResult()->addValue('query' , 'comment_id', $comment_id);
    $this->getResult()->addValue('query' , 'timestamp', $timestamp);

    return true;
  }

  // Description
  public function getDescription() {
    return 'Add a comment to a spot';
  }

  // Parameters
  public function getAllowedParams() {
    return array(
      'commenttext' => array (
        ApiBase::PARAM_TYPE => 'string',
        ApiBase::PARAM_REQUIRED => true
      ),
      'pageid' => array (
        ApiBase::PARAM_TYPE => 'integer',
        ApiBase::PARAM_REQUIRED => true
      ),
      'token' => array (
        ApiBase::PARAM_TYPE => 'string',
        ApiBase::PARAM_REQUIRED => true
      )
    );
  }

  // Describe the parameters
  public function getParamDescription() {
    return array_merge( parent::getParamDescription(), array(
      'commenttext' => 'Comment text',
      'pageid' => 'Page id',
      'token' => 'csrf token'
    ) );
  }

  public function needsToken() {
      return 'csrf';
  }
}

?>
