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
$wgAutoloadClasses['HWDeleteCommentApi'] = "$dir/api/HWDeleteCommentApi.php";
$wgAutoloadClasses['HWGetCommentsApi'] = "$dir/api/HWGetCommentsApi.php";
$wgAutoloadClasses['HWGetCommentsCountApi'] = "$dir/api/HWGetCommentsCountApi.php";
$wgAPIModules['hwaddcomment'] = 'HWAddCommentApi';
$wgAPIModules['hwdeletecomment'] = 'HWDeleteCommentApi';
$wgAPIModules['hwgetcomments'] = 'HWGetCommentsApi';
$wgAPIModules['hwgetcommentscount'] = 'HWGetCommentsCountApi';

return true;
