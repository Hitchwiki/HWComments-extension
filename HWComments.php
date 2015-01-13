<?php

$wgExtensionCredits['HWComments'][] = array(
	'path' => __FILE__,
	'name' => 'HWComments',
	'version' => '0.0.1',
	"authors" => "http://hitchwiki.org"
);

$dir = __DIR__;

//Database hook
$wgAutoloadClasses['HWCommentsHooks'] = "$dir/HWCommentsHooks.php";
$wgHooks['LoadExtensionSchemaUpdates'][] = 'HWCommentsHooks::onLoadExtensionSchemaUpdates';

//Deletion and undeletion hooks
$wgHooks['ArticleDeleteComplete'][] = 'HWCommentsHooks::onArticleDeleteComplete';
$wgHooks['ArticleRevisionUndeleted'][] = 'HWCommentsHooks::onArticleRevisionUndeleted';

//APIs
$wgAutoloadClasses['HWAddCommentApi'] = "$dir/api/HWAddCommentApi.php";
$wgAutoloadClasses['HWGetCommentsApi'] = "$dir/api/HWGetCommentsApi.php";
$wgAPIModules['hwaddcomment'] = 'HWAddCommentApi';
$wgAPIModules['hwgetcomments'] = 'HWGetCommentsApi';

return true;
