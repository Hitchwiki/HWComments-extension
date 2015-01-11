-- SQL schema for HWComments count

CREATE TABLE `hw_comments_count` (
  hw_page_id int unsigned NOT NULL,
  hw_comments_count int unsigned NOT NULL
);

CREATE INDEX hw_page_primary ON hw_comments_count ( hw_page_id );
