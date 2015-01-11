<?php

class HWCommentsHooks {

  public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
    $updater->addExtensionTable( 'hw_comments', dirname( __FILE__ ) . '/sql/db-hw_comments.sql');
    $updater->addExtensionTable( 'hw_comments_count', dirname( __FILE__ ) . '/sql/db-hw_comments_count.sql');

    return true;
  }
}
