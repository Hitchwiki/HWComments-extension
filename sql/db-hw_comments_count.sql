-- SQL schema for HWComments count

CREATE TABLE `hw_comments_count` (
  hw_page_id int unsigned NOT NULL PRIMARY KEY,
  hw_comments_count int unsigned NOT NULL,
  hw_deleted BOOL DEFAULT false
);
