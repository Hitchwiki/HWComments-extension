-- SQL schema for HWComments

CREATE TABLE `hw_comments` (
  hwc_user_id int unsigned NOT NULL,
  hwc_page_id int unsigned NOT NULL,
  hwc_timestamp CHAR(14) NOT NULL,
  hwc_commenttext text
);

CREATE INDEX hw_page_primary ON hw_comments ( hwc_page_id );
