<?php
class HWGetCommentsCountApi extends ApiBase {
  public function execute() {
    // Get parameters
    $params = $this->extractRequestParams();
    $page_ids = $params['pageids'];

    $dbr = wfGetDB( DB_SLAVE );
    $res = $dbr->select(
      array('hw_comments_count'),
      array('hw_comments_count', 'hw_page_id'),
      'hw_page_id IN ('.$page_ids.')'
    );
    foreach( $res as $row ) {
      $vals = array(
        'pageid' => $row->hw_page_id,
        'comments_count' => $row->hw_comments_count,
      );
      $this->getResult()->addValue( array( 'query', 'comment_counts' ), null, $vals );
    }
    if($vals == null) {
      $this->getResult()->addValue('error' , 'info', 'No comment counts for these pages.');
    }

    return true;
  }

  // Description
  public function getDescription() {
      return 'Get the comment counts of the pages.';
  }

  // Parameters.
  public function getAllowedParams() {
      return array(
          'pageids' => array (
              ApiBase::PARAM_TYPE => 'string',
              ApiBase::PARAM_REQUIRED => true
          )
      );
  }

  // Describe the parameter
  public function getParamDescription() {
      return array_merge( parent::getParamDescription(), array(
          'pageids' => 'Ids of the pages',
      ) );
  }
}
