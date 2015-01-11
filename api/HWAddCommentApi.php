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
        'hwc_user_id' => $user_id,
        'hwc_page_id' => $page_id,
        'hwc_commenttext' => $commenttext,
        'hwc_timestamp' => $timestamp
      )
    );

    $this->getResult()->addValue('info' , 'message', 'comment was added');

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
