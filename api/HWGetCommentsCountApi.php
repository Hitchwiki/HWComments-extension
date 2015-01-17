<?php
class HWGetCommentsCountApi extends ApiBase {
  public function execute() {
    $params = $this->extractRequestParams();
    $page_ids = $params['pageid'];

    $dbr = wfGetDB( DB_SLAVE );
    $res = $dbr->select(
      'hw_comments_count',
      array(
        'hw_comments_count',
        'hw_page_id'
      ),
      array(
        'hw_page_id' => $page_ids
      )
    );

    $this->getResult()->addValue( array( 'query' ), 'comment_counts', array() );
    foreach( $res as $row ) {
      $vals = array(
        'pageid' => $row->hw_page_id,
        'comment_count' => $row->hw_comments_count,
      );
      $this->getResult()->addValue( array( 'query', 'comment_counts' ), null, $vals );
    }

    return true;
  }

  // Description
  public function getDescription() {
    return 'Get the comment count of one or more pages';
  }

  // Parameters
  public function getAllowedParams() {
    return array(
      'pageid' => array (
        ApiBase::PARAM_TYPE => 'integer',
        ApiBase::PARAM_REQUIRED => true,
        ApiBase::PARAM_ISMULTI => true
      )
    );
  }

  // Describe the parameters
  public function getParamDescription() {
    return array_merge( parent::getParamDescription(), array(
      'pageid' => 'Page ids, delimited by | (vertical bar)'
    ) );
  }
}
