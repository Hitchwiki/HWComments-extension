<?php
class HWGetCommentsApi extends ApiBase {
  public function execute() {
    // Get parameters
    $params = $this->extractRequestParams();

    $page_id = $params['pageid'];
    $pageObj = $this->getTitleOrPageId($params);

    $dbr = wfGetDB( DB_SLAVE );
    $res = $dbr->select(
      'hw_comments',
      array(
        'hw_user_id',
        'hw_page_id',
        'hw_commenttext',
        'hw_timestamp'
      ),
      'hw_page_id ='.$page_id
    );

    foreach( $res as $row ) {

      $commenttext = new DerivativeRequest(
        $this->getRequest(),
        array(
          'action' => 'parse',
          'text' => $row->hw_commenttext,
          'prop' => 'text',
          'disablepp' => ''
        ),
        true
      );
      $commenttext_api = new ApiMain( $commenttext );
      $commenttext_api->execute();
      $commenttext_data = $commenttext_api->getResultData();

      $vals = array(
        'pageid' => $row->hw_page_id,
        'user_id' => $row->hw_user_id,
        'commenttext' => $commenttext_data['parse']['text']['*'],
        'timestamp' => $row->hw_timestamp
      );
      $this->getResult()->addValue( array( 'query', 'comments' ), null, $vals );
    }
    if($vals == null) {
        $this->getResult()->addValue( array( 'query', 'comments' ), null, null);
    }

    return true;
  }

  // Description
  public function getDescription() {
      return 'Get all the comments of a page.';
  }

  // Parameters.
  public function getAllowedParams() {
      return array(
          'pageid' => array (
              ApiBase::PARAM_TYPE => 'string',
              ApiBase::PARAM_REQUIRED => true
          )
      );
  }

  // Describe the parameter
  public function getParamDescription() {
      return array_merge( parent::getParamDescription(), array(
          'pageid' => 'Id of the page',
      ) );
  }
}
