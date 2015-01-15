<?php
class HWDeleteCommentApi extends ApiBase {
  public function execute() {
    // Get parameters
    $params = $this->extractRequestParams();
    global $wgUser;

    $comment_id = $params['comment_id'];
    $dbr = wfGetDB( DB_MASTER );
    $res = $dbr->select(
      'hw_comments',
      array(
        'hw_user_id',
        'hw_page_id'
      ),
      'hw_comment_id='.$comment_id
    );

    $row = $res->fetchObject();
    if (!$row) {
      $this->getResult()->addValue('error' , 'info', 'comment does not exist');
      return true;
    }

    if ($row->hw_user_id != $wgUser->getId()) {
      $this->getResult()->addValue('error' , 'info', 'comment is authored by another user');
      return true;
    }

    $page_id = $row->hw_page_id;
    $dbr->delete(
      'hw_comments',
      array(
        'hw_comment_id' => $comment_id
      )
    );

    $res = $dbr->query("SELECT COUNT(*) FROM hw_comments WHERE hw_page_id=".$dbr->addQuotes($page_id));
    $row = $res->fetchRow();
    $count = round($row[0]);

    $dbr->upsert(
      'hw_comments_count',
      array(
        'hw_page_id' => $page_id,
        'hw_comments_count' => $count,
      ),
      array('hw_page_id'),
      array(
        'hw_page_id' => $page_id,
        'hw_comments_count' => $count,
      )
    );

    $this->getResult()->addValue('info' , 'message', 'comment was deleted');
    $this->getResult()->addValue('info' , 'page_id', $page_id);
    $this->getResult()->addValue('info' , 'comment_count', $count);

    return true;
  }

  // Description
  public function getDescription() {
      return 'Delete comment from a spot.';
  }

  // Parameters.
  public function getAllowedParams() {
      return array(
          'comment_id' => array (
              ApiBase::PARAM_TYPE => 'integer',
              ApiBase::PARAM_REQUIRED => true
          ),
          'token' => array (
              ApiBase::PARAM_TYPE => 'string',
              ApiBase::PARAM_REQUIRED => true
          )
      );
  }

  // Describe the parameter
  public function getParamDescription() {
      return array_merge( parent::getParamDescription(), array(
          'comment_id' => 'Comment id',
          'token' => 'User edit token'
      ) );
  }

  public function needsToken() {
      return 'csrf';
  }

}
