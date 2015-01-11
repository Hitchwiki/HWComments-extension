<?php
class HWAddCommentApi extends ApiBase {
  public function execute() {
    // Get parameters
    $params = $this->extractRequestParams();
    global $wgUser;

    $page_id = $params['pageid'];
    $user_id = $wgUser->getId();
    $commenttext = $params['commenttext'];
    $timestamp = wfTimestampNow();

    $dbr = wfGetDB( DB_MASTER );

    $dbr->insert(
      'hw_comments',
      array(
        'hw_user_id' => $user_id,
        'hw_page_id' => $page_id,
        'hw_commenttext' => $commenttext,
        'hw_timestamp' => $timestamp
      )
    );

    $res = $dbr->query("SELECT COUNT(hw_timestamp) FROM hw_comments WHERE hw_page_id=".$dbr->addQuotes($page_id));
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

    $this->getResult()->addValue('info' , 'message', 'comment was added');
    $this->getResult()->addValue('info' , 'page_id', $page_id);
    $this->getResult()->addValue('info' , 'comment_count', $count);

    return true;
  }

  // Description
  public function getDescription() {
      return 'Add a comment to a spot.';
  }

  // Parameters.
  public function getAllowedParams() {
      return array(
          'commenttext' => array (
              ApiBase::PARAM_TYPE => 'string',
              ApiBase::PARAM_REQUIRED => true
          ),
          'pageid' => array (
              ApiBase::PARAM_TYPE => 'string',
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
          'commenttext' => 'Text of the comment',
          'pageid' => 'Id of the spot to comment',
          'token' => 'User edit token'
      ) );
  }

  public function needsToken() {
      return 'csrf';
  }

}
