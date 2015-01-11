-- SQL schema for HWComments

CREATE TABLE `hw_comments` (
  hw_user_id int unsigned NOT NULL,
  hw_page_id int unsigned NOT NULL,
  hw_timestamp CHAR(14) NOT NULL,
  hw_commenttext text
);

CREATE INDEX hw_page_primary ON hw_comments ( hw_page_id );
