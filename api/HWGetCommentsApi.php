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
        'hwc_user_id',
        'hwc_page_id',
        'hwc_commenttext',
        'hwc_timestamp'
      ),
      'hwc_page_id ='.$page_id
    );

    foreach( $res as $row ) {

      $commenttext = new DerivativeRequest(
        $this->getRequest(),
        array(
          'action' => 'parse',
          'text' => $row->hwc_commenttext,
          'prop' => 'text',
          'disablepp' => ''
        ),
        true
      );
      $commenttext_api = new ApiMain( $commenttext );
      $commenttext_api->execute();
      $commenttext_data = $commenttext_api->getResultData();

      $vals = array(
        'pageid' => $row->hwc_page_id,
        'user_id' => $row->hwc_user_id,
        'commenttext' => $commenttext_data['parse']['text']['*'],
        'timestamp' => $row->hwc_timestamp
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
