<?php

class HWCommentsHooks {

  public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
    $updater->addExtensionTable( 'hw_comments', dirname( __FILE__ ) . '/sql/db-hw_comments.sql');

    return true;
  }
}
