#
# Table structure for: address_book
#
CREATE TABLE address_book (
  address_book_id int(11) NOT NULL auto_increment,
  customers_id int(11) NOT NULL DEFAULT '0',
  entry_gender char(1) NOT NULL DEFAULT '',
  entry_company varchar(32) DEFAULT NULL,
  entry_firstname varchar(32) NOT NULL DEFAULT '',
  entry_lastname varchar(32) NOT NULL DEFAULT '',
  entry_street_address varchar(64) NOT NULL DEFAULT '',
  entry_suburb varchar(32) DEFAULT NULL,
  entry_postcode varchar(10) NOT NULL DEFAULT '',
  entry_city varchar(32) NOT NULL DEFAULT '',
  entry_state varchar(32) DEFAULT NULL,
  entry_country_id int(11) NOT NULL DEFAULT '0',
  entry_zone_id int(11) NOT NULL DEFAULT '0',
  entry_telephone varchar(32) NOT NULL DEFAULT '',
  entry_fax varchar(32) NOT NULL DEFAULT '',
  entry_email_address varchar(96) NOT NULL DEFAULT '',
  PRIMARY KEY (address_book_id),
  KEY idx_address_book_customers_id (customers_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: address_format
#
CREATE TABLE address_format (
  address_format_id int(11) NOT NULL auto_increment,
  address_format varchar(128) NOT NULL DEFAULT '',
  address_summary varchar(48) NOT NULL DEFAULT '',
  PRIMARY KEY (address_format_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: admin
#
CREATE TABLE admin (
  admin_id int(11) NOT NULL auto_increment,
  admin_groups_id int(11) DEFAULT NULL,
  admin_firstname varchar(32) NOT NULL DEFAULT '',
  admin_lastname varchar(32) DEFAULT NULL,
  admin_email_address varchar(96) NOT NULL DEFAULT '',
  admin_password varchar(40) NOT NULL DEFAULT '',
  admin_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  admin_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  admin_logdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  admin_lognum int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (admin_id),
  UNIQUE KEY admin_email_address (admin_email_address)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: admin_files
#
CREATE TABLE admin_files (
  admin_files_id int(11) NOT NULL auto_increment,
  admin_files_name varchar(64) NOT NULL DEFAULT '',
  admin_files_is_boxes tinyint(5) NOT NULL DEFAULT '0',
  admin_files_to_boxes int(11) NOT NULL DEFAULT '0',
  admin_groups_id set('1','2','3','4','5','6') NOT NULL DEFAULT '1',
  PRIMARY KEY (admin_files_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci AUTO_INCREMENT=10000;


#
# Table structure for: admin_groups
#
CREATE TABLE admin_groups (
  admin_groups_id int(11) NOT NULL auto_increment,
  admin_groups_name varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (admin_groups_id),
  UNIQUE KEY admin_groups_name (admin_groups_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: algozone_fraud_queries
#
CREATE TABLE algozone_fraud_queries (
  order_id varchar(6) NOT NULL DEFAULT '',
  ip_address varchar(30) NOT NULL DEFAULT '',
  last_date_queried datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  fraud_level varchar(5) DEFAULT NULL,
  err_message varchar(150) DEFAULT NULL,
  distance_m varchar(10) DEFAULT NULL,
  distance_k varchar(10) DEFAULT NULL,
  country_code varchar(21) DEFAULT NULL,
  is_country_match char(3) DEFAULT NULL,
  is_free_email char(3) DEFAULT NULL,
  is_customer_phone_inloc char(3) DEFAULT NULL,
  proxy_level varchar(5) DEFAULT NULL,
  spam_level varchar(5) DEFAULT NULL,
  is_high_risk_country varchar(5) DEFAULT NULL,
  is_anonymous_proxy char(3) DEFAULT NULL,
  ip_city varchar(21) DEFAULT NULL,
  ip_region varchar(21) DEFAULT NULL,
  ip_isp varchar(30) DEFAULT NULL,
  ip_org varchar(30) DEFAULT NULL,
  ip_latitude varchar(21) DEFAULT NULL,
  ip_longitude varchar(21) DEFAULT NULL,
  bin_country_code varchar(5) DEFAULT NULL,
  is_bin_match varchar(5) DEFAULT NULL,
  is_bank_name_match varchar(5) DEFAULT NULL,
  bank_name varchar(20) DEFAULT NULL,
  is_bank_phone_match varchar(5) DEFAULT NULL,
  bank_phone varchar(15) DEFAULT NULL,
  KEY order_id (order_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: article_reviews
#
CREATE TABLE article_reviews (
  reviews_id int(11) NOT NULL auto_increment,
  articles_id int(11) NOT NULL DEFAULT '0',
  customers_id int(11) NOT NULL DEFAULT '0',
  customers_name varchar(64) NOT NULL DEFAULT '',
  reviews_rating int(1) DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  reviews_read int(5) NOT NULL DEFAULT '0',
  approved tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (reviews_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: article_reviews_description
#
CREATE TABLE article_reviews_description (
  reviews_id int(11) NOT NULL DEFAULT '0',
  languages_id int(11) NOT NULL DEFAULT '0',
  reviews_text text NOT NULL DEFAULT '',
  PRIMARY KEY (reviews_id,languages_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: articles
#
CREATE TABLE articles (
  articles_id int(11) NOT NULL auto_increment,
  articles_date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  articles_last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  articles_date_available datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  articles_status tinyint(1) NOT NULL DEFAULT '0',
  authors_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (articles_id),
  KEY idx_articles_date_added (articles_date_added)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: articles_description
#
CREATE TABLE articles_description (
  articles_id int(11) NOT NULL auto_increment,
  language_id int(11) NOT NULL DEFAULT '1',
  articles_name varchar(64) NOT NULL DEFAULT '',
  articles_description text DEFAULT NULL,
  articles_url varchar(255) DEFAULT NULL,
  articles_viewed int(5) DEFAULT '0',
  articles_head_title_tag varchar(80) DEFAULT NULL,
  articles_head_desc_tag text DEFAULT NULL,
  articles_head_keywords_tag text DEFAULT NULL,
  PRIMARY KEY (articles_id,language_id),
  KEY articles_name (articles_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: articles_to_topics
#
CREATE TABLE articles_to_topics (
  articles_id int(11) NOT NULL DEFAULT '0',
  topics_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (articles_id,topics_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: articles_xsell
#
CREATE TABLE articles_xsell (
  ID int(10) NOT NULL auto_increment,
  articles_id int(11) NOT NULL DEFAULT '0',
  xsell_id int(11) NOT NULL DEFAULT '0',
  sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  PRIMARY KEY (ID)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: authors
#
CREATE TABLE authors (
  authors_id int(11) NOT NULL auto_increment,
  authors_name varchar(64) NOT NULL DEFAULT '',
  authors_image varchar(64) DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (authors_id),
  KEY IDX_AUTHORS_NAME (authors_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: authors_info
#
CREATE TABLE authors_info (
  authors_id int(11) NOT NULL DEFAULT '0',
  languages_id int(11) NOT NULL DEFAULT '0',
  authors_description text DEFAULT NULL,
  authors_url varchar(255) NOT NULL DEFAULT '',
  url_clicked int(5) NOT NULL DEFAULT '0',
  date_last_click datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (authors_id,languages_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: banners
#
CREATE TABLE banners (
  banners_id int(11) NOT NULL auto_increment,
  banners_title varchar(64) NOT NULL DEFAULT '',
  banners_url varchar(255) NOT NULL DEFAULT '',
  banners_image varchar(64) NOT NULL DEFAULT '',
  banners_group varchar(10) NOT NULL DEFAULT '',
  banners_html_text text DEFAULT NULL,
  expires_impressions int(7) DEFAULT '0',
  expires_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_scheduled datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_status_change datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  status tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (banners_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: banners_history
#
CREATE TABLE banners_history (
  banners_history_id int(11) NOT NULL auto_increment,
  banners_id int(11) NOT NULL DEFAULT '0',
  banners_shown int(5) NOT NULL DEFAULT '0',
  banners_clicked int(5) NOT NULL DEFAULT '0',
  banners_history_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (banners_history_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: branding_description
#
CREATE TABLE branding_description (
  store_brand_image varchar(64) NOT NULL DEFAULT '',
  store_brand_slogan varchar(96) NOT NULL DEFAULT '',
  store_brand_telephone varchar(16) NOT NULL DEFAULT '',
  store_brand_fax varchar(16) NOT NULL DEFAULT '',
  store_brand_homepage varchar(255) NOT NULL DEFAULT '',
  store_brand_name varchar(64) NOT NULL DEFAULT '',
  store_brand_support_email varchar(128) NOT NULL DEFAULT '',
  store_brand_support_phone varchar(16) NOT NULL DEFAULT '',
  language_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (language_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: card_blacklist
#
CREATE TABLE card_blacklist (
  blacklist_id int(5) NOT NULL auto_increment,
  blacklist_card_number varchar(20) NOT NULL DEFAULT '',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (blacklist_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: categories
#
CREATE TABLE categories (
  categories_id int(11) NOT NULL auto_increment,
  categories_image varchar(64) DEFAULT NULL,
  parent_id int(11) NOT NULL DEFAULT '0',
  sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (categories_id),
  KEY idx_categories_parent_id (parent_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: categories_description
#
CREATE TABLE categories_description (
  categories_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  categories_name varchar(64) NOT NULL DEFAULT '',
  categories_heading_title varchar(64) DEFAULT NULL,
  categories_description text DEFAULT NULL,
  categories_head_title_tag varchar(80) DEFAULT NULL,
  categories_head_desc_tag longtext NOT NULL DEFAULT '',
  categories_head_keywords_tag longtext NOT NULL DEFAULT '',
  categories_htc_title_tag varchar(80) DEFAULT NULL,
  categories_htc_desc_tag longtext DEFAULT NULL,
  categories_htc_keywords_tag longtext DEFAULT NULL,
  categories_htc_description longtext DEFAULT NULL,
  PRIMARY KEY (categories_id,language_id),
  KEY idx_categories_name (categories_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: components
#
CREATE TABLE components (
  components_id int(11) NOT NULL auto_increment,
  serial_1 varchar(255) NOT NULL DEFAULT '',
  serial_2 varchar(255) NOT NULL DEFAULT '',
  status tinyint(1) NOT NULL DEFAULT '0',
  last_validated date NOT NULL DEFAULT '0000-00-00',
  validation_product varchar(255) NOT NULL DEFAULT '',
  expiration_date date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (components_id,validation_product)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: configuration
#
CREATE TABLE configuration (
  configuration_id int(11) NOT NULL auto_increment,
  configuration_title varchar(64) NOT NULL DEFAULT '',
  configuration_key varchar(64) NOT NULL DEFAULT '',
  configuration_value varchar(255) NOT NULL DEFAULT '',
  configuration_description varchar(255) NOT NULL DEFAULT '',
  configuration_group_id int(11) NOT NULL DEFAULT '0',
  sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  use_function varchar(255) DEFAULT NULL,
  set_function varchar(255) DEFAULT NULL,
  PRIMARY KEY (configuration_id),
  UNIQUE KEY idx_configuration_key (configuration_key)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci AUTO_INCREMENT=3275;


#
# Table structure for: configuration_group
#
CREATE TABLE configuration_group (
  configuration_group_id int(11) NOT NULL auto_increment,
  configuration_group_title varchar(64) NOT NULL DEFAULT '',
  configuration_group_description varchar(255) NOT NULL DEFAULT '',
  sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  visible tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (configuration_group_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci AUTO_INCREMENT=5000;


#
# Table structure for: counter
#
CREATE TABLE counter (
  startdate char(8) DEFAULT NULL,
  counter int(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: counter_history
#
CREATE TABLE counter_history (
  month char(8) DEFAULT NULL,
  counter int(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: countries
#
CREATE TABLE countries (
  countries_id int(11) NOT NULL auto_increment,
  countries_name varchar(64) NOT NULL DEFAULT '',
  countries_iso_code_2 char(2) NOT NULL DEFAULT '',
  countries_iso_code_3 char(3) NOT NULL DEFAULT '',
  address_format_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (countries_id),
  KEY IDX_COUNTRIES_NAME (countries_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: coupon_email_track
#
CREATE TABLE coupon_email_track (
  unique_id int(11) NOT NULL auto_increment,
  coupon_id int(11) NOT NULL DEFAULT '0',
  customer_id_sent int(11) NOT NULL DEFAULT '0',
  sent_firstname varchar(32) DEFAULT NULL,
  sent_lastname varchar(32) DEFAULT NULL,
  emailed_to varchar(32) DEFAULT NULL,
  date_sent datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (unique_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: coupon_gv_customer
#
CREATE TABLE coupon_gv_customer (
  customer_id int(5) NOT NULL DEFAULT '0',
  amount decimal(8,4) NOT NULL DEFAULT '0.0000',
  KEY idx_customer_id (customer_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: coupon_gv_queue
#
CREATE TABLE coupon_gv_queue (
  unique_id int(5) NOT NULL auto_increment,
  customer_id int(5) NOT NULL DEFAULT '0',
  order_id int(5) NOT NULL DEFAULT '0',
  amount decimal(8,4) NOT NULL DEFAULT '0.0000',
  date_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  ipaddr varchar(32) NOT NULL DEFAULT '',
  release_flag char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (unique_id),
  KEY uid (unique_id,customer_id,order_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: coupon_redeem_track
#
CREATE TABLE coupon_redeem_track (
  unique_id int(11) NOT NULL auto_increment,
  coupon_id int(11) NOT NULL DEFAULT '0',
  customer_id int(11) NOT NULL DEFAULT '0',
  redeem_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  redeem_ip varchar(32) NOT NULL DEFAULT '',
  order_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (unique_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: coupons
#
CREATE TABLE coupons (
  coupon_id int(11) NOT NULL auto_increment,
  coupon_type char(1) NOT NULL DEFAULT 'F',
  coupon_code varchar(32) NOT NULL DEFAULT '',
  coupon_amount decimal(8,4) NOT NULL DEFAULT '0.0000',
  coupon_minimum_order decimal(8,4) NOT NULL DEFAULT '0.0000',
  coupon_start_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  coupon_expire_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  uses_per_coupon int(5) NOT NULL DEFAULT '1',
  uses_per_user int(5) NOT NULL DEFAULT '0',
  restrict_to_products varchar(255) DEFAULT NULL,
  restrict_to_categories varchar(255) DEFAULT NULL,
  restrict_to_customers text DEFAULT NULL,
  coupon_active char(1) NOT NULL DEFAULT 'Y',
  date_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  coupon_sale_exclude tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (coupon_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: coupons_description
#
CREATE TABLE coupons_description (
  coupon_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '0',
  coupon_name varchar(32) NOT NULL DEFAULT '',
  coupon_description text DEFAULT NULL,
  PRIMARY KEY (coupon_id,language_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: currencies
#
CREATE TABLE currencies (
  currencies_id int(11) NOT NULL auto_increment,
  title varchar(32) NOT NULL DEFAULT '',
  code char(3) NOT NULL DEFAULT '',
  symbol_left varchar(12) DEFAULT NULL,
  symbol_right varchar(12) DEFAULT NULL,
  decimal_point char(1) DEFAULT NULL,
  thousands_point char(1) DEFAULT NULL,
  decimal_places char(1) DEFAULT NULL,
  value float(13,8) DEFAULT NULL,
  last_updated datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (currencies_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: customers
#
CREATE TABLE customers (
  customers_id int(11) NOT NULL auto_increment,
  purchased_without_account tinyint(1) unsigned NOT NULL DEFAULT '0',
  customers_gender char(1) NOT NULL DEFAULT '',
  customers_firstname varchar(32) NOT NULL DEFAULT '',
  customers_lastname varchar(32) NOT NULL DEFAULT '',
  customers_dob datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  customers_email_address varchar(96) NOT NULL DEFAULT '',
  customers_default_address_id int(11) NOT NULL DEFAULT '0',
  customers_password varchar(40) NOT NULL DEFAULT '',
  customers_newsletter char(1) DEFAULT NULL,
  customers_selected_template varchar(20) DEFAULT NULL,
  customers_validation_code varchar(48) NOT NULL DEFAULT '',
  customers_validation char(1) NOT NULL DEFAULT '0',
  customers_email_registered varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (customers_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: customers_basket
#
CREATE TABLE customers_basket (
  customers_basket_id int(11) NOT NULL auto_increment,
  customers_id int(11) NOT NULL DEFAULT '0',
  products_id tinytext NOT NULL DEFAULT '',
  customers_basket_quantity int(2) NOT NULL DEFAULT '0',
  final_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  customers_basket_date_added varchar(8) DEFAULT NULL,
  PRIMARY KEY (customers_basket_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: customers_basket_attributes
#
CREATE TABLE customers_basket_attributes (
  customers_basket_attributes_id int(11) NOT NULL auto_increment,
  customers_id int(11) NOT NULL DEFAULT '0',
  products_id tinytext NOT NULL DEFAULT '',
  products_options_id int(11) NOT NULL DEFAULT '0',
  products_options_value_id int(11) NOT NULL DEFAULT '0',
  products_options_value_text text NOT NULL DEFAULT '',
  PRIMARY KEY (customers_basket_attributes_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: customers_comments
#
CREATE TABLE customers_comments (
  customers_comments_id int(11) NOT NULL auto_increment,
  customers_id int(11) NOT NULL DEFAULT '0',
  admin_id int(11) NOT NULL DEFAULT '0',
  admin_name varchar(64) NOT NULL DEFAULT '',
  comments text NOT NULL DEFAULT '',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (customers_comments_id),
  KEY customers_id (customers_id),
  KEY date_added (date_added)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: customers_info
#
CREATE TABLE customers_info (
  customers_info_id int(11) NOT NULL DEFAULT '0',
  customers_info_date_of_last_logon datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  customers_info_number_of_logons int(5) NOT NULL DEFAULT '0',
  customers_info_date_account_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  customers_info_date_account_last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  global_product_notifications tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (customers_info_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: customers_wishlist
#
CREATE TABLE customers_wishlist (
  products_id int(13) NOT NULL DEFAULT '0',
  customers_id int(13) NOT NULL DEFAULT '0',
  products_model varchar(25) DEFAULT NULL,
  products_name varchar(128) NOT NULL DEFAULT '',
  products_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  final_price decimal(13,2) NOT NULL DEFAULT '0.00',
  products_quantity int(2) NOT NULL DEFAULT '0',
  wishlist_name varchar(64) DEFAULT NULL,
  KEY idx_customers_products_id (customers_id,products_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: customers_wishlist_attributes
#
CREATE TABLE customers_wishlist_attributes (
  customers_wishlist_attributes_id int(11) NOT NULL auto_increment,
  customers_id int(11) NOT NULL DEFAULT '0',
  products_id int(11) NOT NULL DEFAULT '0',
  products_options_id int(11) NOT NULL DEFAULT '0',
  products_options_value_id varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (customers_wishlist_attributes_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: data_cat
#
CREATE TABLE data_cat (
  cat_id int(11) NOT NULL DEFAULT '0',
  cat_tree varchar(254) DEFAULT NULL,
  PRIMARY KEY (cat_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: data_files
#
CREATE TABLE data_files (
  data_files_id int(11) NOT NULL auto_increment,
  data_name varchar(15) NOT NULL DEFAULT 'No Name',
  data_files_type varchar(11) NOT NULL DEFAULT 'basic',
  data_files_disc varchar(32) NOT NULL DEFAULT 'not configured',
  data_files_type1 varchar(10) NOT NULL DEFAULT 'product',
  data_files_service varchar(10) DEFAULT 'froogle',
  data_status int(1) NOT NULL DEFAULT '0',
  data_files_name varchar(64) NOT NULL DEFAULT 'Not configured',
  data_image_url varchar(64) DEFAULT NULL,
  data_product_url varchar(64) DEFAULT NULL,
  data_ftp_server varchar(32) DEFAULT 'hedwig.google.com',
  data_ftp_user_name varchar(32) DEFAULT NULL,
  data_ftp_user_pass varchar(32) DEFAULT NULL,
  data_ftp_directory varchar(64) DEFAULT NULL,
  data_tax_class_id int(11) NOT NULL DEFAULT '0',
  data_convert_cur varchar(5) DEFAULT 'false',
  data_cur_use varchar(5) NOT NULL DEFAULT 'true',
  data_cur char(3) DEFAULT 'USD',
  data_lang_use varchar(5) NOT NULL DEFAULT 'true',
  data_lang_char char(2) DEFAULT 'en',
  PRIMARY KEY (data_files_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: email_subjects
#
CREATE TABLE email_subjects (
  email_subjects_id int(11) NOT NULL auto_increment,
  email_subjects_name varchar(64) NOT NULL DEFAULT '',
  email_subjects_category int(1) DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (email_subjects_id),
  KEY IDX_EMAIL_SUBJECTS_NAME (email_subjects_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: events_calendar
#
CREATE TABLE events_calendar (
  event_id int(3) NOT NULL auto_increment,
  language_id int(11) NOT NULL DEFAULT '1',
  start_date date NOT NULL DEFAULT '0000-00-00',
  end_date varchar(20) DEFAULT NULL,
  title varchar(255) DEFAULT NULL,
  event_image varchar(64) DEFAULT NULL,
  link varchar(255) DEFAULT NULL,
  OSC_link varchar(255) DEFAULT NULL,
  description text DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (event_id,language_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: faq
#
CREATE TABLE faq (
  faq_id tinyint(3) unsigned NOT NULL auto_increment,
  visible enum('1','0') NOT NULL DEFAULT '1',
  v_order tinyint(3) unsigned NOT NULL DEFAULT '0',
  question text NOT NULL DEFAULT '',
  answer text NOT NULL DEFAULT '',
  date date NOT NULL DEFAULT '0000-00-00',
  language varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (faq_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: faq_categories
#
CREATE TABLE faq_categories (
  categories_id tinyint(3) unsigned NOT NULL auto_increment,
  categories_image varchar(64) DEFAULT NULL,
  categories_sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  categories_date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  categories_last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  categories_status tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (categories_id),
  KEY idx_categories_date_added (categories_date_added)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: faq_categories_description
#
CREATE TABLE faq_categories_description (
  categories_id tinyint(3) unsigned NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  categories_name varchar(32) NOT NULL DEFAULT '',
  categories_description text DEFAULT NULL,
  PRIMARY KEY (categories_id,language_id),
  KEY idx_categories_name (categories_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: faq_to_categories
#
CREATE TABLE faq_to_categories (
  faq_id tinyint(3) unsigned NOT NULL DEFAULT '0',
  categories_id tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (faq_id,categories_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: featured
#
CREATE TABLE featured (
  featured_id int(11) NOT NULL auto_increment,
  products_id int(11) NOT NULL DEFAULT '0',
  featured_date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  featured_last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  expires_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_status_change datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  status tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (featured_id),
  KEY idx_products_id (products_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: geo_zones
#
CREATE TABLE geo_zones (
  geo_zone_id int(11) NOT NULL auto_increment,
  geo_zone_name varchar(32) NOT NULL DEFAULT '',
  geo_zone_description varchar(255) NOT NULL DEFAULT '',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (geo_zone_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: infobox_configuration
#
CREATE TABLE infobox_configuration (
  template_id int(11) NOT NULL DEFAULT '0',
  infobox_id int(11) NOT NULL auto_increment,
  infobox_file_name varchar(64) NOT NULL DEFAULT '',
  infobox_define varchar(64) NOT NULL DEFAULT 'BOX_HEADING_',
  infobox_display varchar(5) NOT NULL DEFAULT '',
  display_in_column varchar(64) NOT NULL DEFAULT 'left',
  location int(3) NOT NULL DEFAULT '0',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  box_heading varchar(64) NOT NULL DEFAULT '',
  box_template varchar(64) NOT NULL DEFAULT 'infobox',
  box_heading_font_color varchar(10) NOT NULL DEFAULT '#000000',
  PRIMARY KEY (infobox_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: infobox_heading
#
CREATE TABLE infobox_heading (
  infobox_id int(11) NOT NULL DEFAULT '0',
  languages_id int(11) NOT NULL DEFAULT '0',
  box_heading varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (infobox_id,languages_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: languages
#
CREATE TABLE languages (
  languages_id int(11) NOT NULL auto_increment,
  name varchar(32) NOT NULL DEFAULT '',
  code char(2) NOT NULL DEFAULT '',
  image varchar(64) DEFAULT NULL,
  directory varchar(32) DEFAULT NULL,
  sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  PRIMARY KEY (languages_id),
  KEY IDX_LANGUAGES_NAME (name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: link_categories
#
CREATE TABLE link_categories (
  link_categories_id int(11) NOT NULL auto_increment,
  link_categories_image varchar(64) DEFAULT NULL,
  link_categories_sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  link_categories_date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  link_categories_last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  link_categories_status tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (link_categories_id),
  KEY idx_link_categories_date_added (link_categories_date_added)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: link_categories_description
#
CREATE TABLE link_categories_description (
  link_categories_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  link_categories_name varchar(32) NOT NULL DEFAULT '',
  link_categories_description text DEFAULT NULL,
  PRIMARY KEY (link_categories_id,language_id),
  KEY idx_link_categories_name (link_categories_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: links
#
CREATE TABLE links (
  links_id int(11) NOT NULL auto_increment,
  links_url varchar(255) DEFAULT NULL,
  links_reciprocal_url varchar(255) DEFAULT NULL,
  links_image_url varchar(255) DEFAULT NULL,
  links_contact_name varchar(64) DEFAULT NULL,
  links_contact_email varchar(96) DEFAULT NULL,
  links_date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  links_last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  links_status int(11) NOT NULL DEFAULT '0',
  links_clicked int(11) NOT NULL DEFAULT '0',
  links_rating tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (links_id),
  KEY idx_links_date_added (links_date_added)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: links_description
#
CREATE TABLE links_description (
  links_id int(11) NOT NULL auto_increment,
  language_id int(11) NOT NULL DEFAULT '1',
  links_title varchar(64) NOT NULL DEFAULT '',
  links_description text DEFAULT NULL,
  PRIMARY KEY (links_id,language_id),
  KEY links_title (links_title)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: links_status
#
CREATE TABLE links_status (
  links_status_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  links_status_name varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (links_status_id,language_id),
  KEY idx_links_status_name (links_status_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: links_to_link_categories
#
CREATE TABLE links_to_link_categories (
  links_id int(11) NOT NULL DEFAULT '0',
  link_categories_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (links_id,link_categories_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: manufacturers
#
CREATE TABLE manufacturers (
  manufacturers_id int(11) NOT NULL auto_increment,
  manufacturers_name varchar(32) NOT NULL DEFAULT '',
  manufacturers_image varchar(64) DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (manufacturers_id),
  KEY IDX_MANUFACTURERS_NAME (manufacturers_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: manufacturers_info
#
CREATE TABLE manufacturers_info (
  manufacturers_id int(11) NOT NULL DEFAULT '0',
  languages_id int(11) NOT NULL DEFAULT '0',
  manufacturers_url varchar(255) NOT NULL DEFAULT '',
  url_clicked int(5) NOT NULL DEFAULT '0',
  date_last_click datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  manufacturers_htc_title_tag varchar(80) DEFAULT NULL,
  manufacturers_htc_desc_tag longtext DEFAULT NULL,
  manufacturers_htc_keywords_tag longtext DEFAULT NULL,
  PRIMARY KEY (manufacturers_id,languages_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: newsletters
#
CREATE TABLE newsletters (
  newsletters_id int(11) NOT NULL auto_increment,
  title varchar(255) NOT NULL DEFAULT '',
  content text NOT NULL DEFAULT '',
  module varchar(255) NOT NULL DEFAULT '',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_sent datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  status tinyint(1) NOT NULL DEFAULT '0',
  locked tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (newsletters_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: orders
#
CREATE TABLE orders (
  orders_id int(11) NOT NULL auto_increment,
  customers_id int(11) NOT NULL DEFAULT '0',
  customers_name varchar(64) NOT NULL DEFAULT '',
  customers_company varchar(32) DEFAULT NULL,
  customers_street_address varchar(64) NOT NULL DEFAULT '',
  customers_suburb varchar(32) DEFAULT NULL,
  customers_city varchar(32) NOT NULL DEFAULT '',
  customers_postcode varchar(10) NOT NULL DEFAULT '',
  customers_state varchar(32) DEFAULT NULL,
  customers_country varchar(32) NOT NULL DEFAULT '',
  customers_telephone varchar(32) NOT NULL DEFAULT '',
  customers_email_address varchar(96) NOT NULL DEFAULT '',
  customers_address_format_id int(5) NOT NULL DEFAULT '0',
  delivery_name varchar(64) NOT NULL DEFAULT '',
  delivery_company varchar(32) DEFAULT NULL,
  delivery_telephone varchar(32) NOT NULL DEFAULT '',
  delivery_fax varchar(32) NOT NULL DEFAULT '',
  delivery_email_address varchar(96) NOT NULL DEFAULT '',
  delivery_street_address varchar(64) NOT NULL DEFAULT '',
  delivery_suburb varchar(32) DEFAULT NULL,
  delivery_city varchar(32) NOT NULL DEFAULT '',
  delivery_postcode varchar(10) NOT NULL DEFAULT '',
  delivery_state varchar(32) DEFAULT NULL,
  delivery_country varchar(32) NOT NULL DEFAULT '',
  delivery_address_format_id int(5) NOT NULL DEFAULT '0',
  billing_name varchar(64) NOT NULL DEFAULT '',
  billing_company varchar(32) DEFAULT NULL,
  billing_telephone varchar(32) NOT NULL DEFAULT '',
  billing_fax varchar(32) NOT NULL DEFAULT '',
  billing_email_address varchar(96) NOT NULL DEFAULT '',
  billing_street_address varchar(64) NOT NULL DEFAULT '',
  billing_suburb varchar(32) DEFAULT NULL,
  billing_city varchar(32) NOT NULL DEFAULT '',
  billing_postcode varchar(10) NOT NULL DEFAULT '',
  billing_state varchar(32) DEFAULT NULL,
  billing_country varchar(32) NOT NULL DEFAULT '',
  billing_address_format_id int(5) NOT NULL DEFAULT '0',
  payment_method varchar(32) NOT NULL DEFAULT '',
  payment_info text DEFAULT NULL,
  payment_id int(11) NOT NULL DEFAULT '0',
  cc_type varchar(20) DEFAULT NULL,
  cc_owner varchar(64) DEFAULT NULL,
  cc_number varchar(90) DEFAULT NULL,
  cc_expires varchar(90) DEFAULT NULL,
  cc_start varchar(4) DEFAULT NULL,
  cc_issue char(3) DEFAULT NULL,
  cc_bank_phone varchar(32) NOT NULL DEFAULT '',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_purchased datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  orders_status int(5) NOT NULL DEFAULT '0',
  orders_date_finished datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  currency char(3) DEFAULT NULL,
  currency_value decimal(14,6) DEFAULT NULL,
  account_name varchar(64) DEFAULT NULL,
  account_number varchar(32) DEFAULT NULL,
  po_number varchar(12) DEFAULT NULL,
  purchased_without_account tinyint(1) unsigned NOT NULL DEFAULT '0',
  paypal_ipn_id int(11) NOT NULL DEFAULT '0',
  ipaddy varchar(15) NOT NULL DEFAULT '',
  ipisp varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (orders_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: orders_pay_methods
#
CREATE TABLE orders_pay_methods (
  pay_methods_id int(11) NOT NULL auto_increment,
  pay_method_language int(11) NOT NULL DEFAULT '1',
  pay_method_sort smallint(3) unsigned NOT NULL DEFAULT '9999',
  pay_method varchar(255) NOT NULL DEFAULT '',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (pay_methods_id,pay_method_language)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: orders_products
#
CREATE TABLE orders_products (
  orders_products_id int(11) NOT NULL auto_increment,
  orders_id int(11) NOT NULL DEFAULT '0',
  products_id int(11) NOT NULL DEFAULT '0',
  products_model varchar(25) DEFAULT NULL,
  products_name varchar(128) NOT NULL DEFAULT '',
  products_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  final_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_tax decimal(7,4) NOT NULL DEFAULT '0.0000',
  products_quantity int(2) NOT NULL DEFAULT '0',
  products_returned tinyint(2) unsigned DEFAULT '0',
  products_exchanged tinyint(2) NOT NULL DEFAULT '0',
  products_exchanged_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (orders_products_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: orders_products_attributes
#
CREATE TABLE orders_products_attributes (
  orders_products_attributes_id int(11) NOT NULL auto_increment,
  orders_id int(11) NOT NULL DEFAULT '0',
  orders_products_id int(11) NOT NULL DEFAULT '0',
  products_options varchar(32) NOT NULL DEFAULT '',
  products_options_values varchar(64) NOT NULL DEFAULT '',
  options_values_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  price_prefix char(1) NOT NULL DEFAULT '',
  products_options_id int(11) NOT NULL DEFAULT '0',
  products_options_values_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (orders_products_attributes_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: orders_products_download
#
CREATE TABLE orders_products_download (
  orders_products_download_id int(11) NOT NULL auto_increment,
  orders_id int(11) NOT NULL DEFAULT '0',
  orders_products_id int(11) NOT NULL DEFAULT '0',
  orders_products_filename varchar(255) NOT NULL DEFAULT '',
  download_maxdays int(2) NOT NULL DEFAULT '0',
  download_count int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (orders_products_download_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: orders_session_info
#
CREATE TABLE orders_session_info (
  txn_signature varchar(32) NOT NULL DEFAULT '',
  orders_id int(11) NOT NULL DEFAULT '0',
  payment varchar(32) NOT NULL DEFAULT '',
  payment_title varchar(32) NOT NULL DEFAULT '',
  payment_amount decimal(7,2) NOT NULL DEFAULT '0.00',
  payment_currency char(3) NOT NULL DEFAULT '',
  payment_currency_val float(13,8) DEFAULT NULL,
  sendto int(11) NOT NULL DEFAULT '1',
  billto int(11) NOT NULL DEFAULT '1',
  language varchar(32) NOT NULL DEFAULT '',
  language_id int(11) NOT NULL DEFAULT '1',
  currency char(3) NOT NULL DEFAULT '',
  currency_value float(13,8) DEFAULT NULL,
  firstname varchar(32) NOT NULL DEFAULT '',
  lastname varchar(32) NOT NULL DEFAULT '',
  content_type varchar(32) NOT NULL DEFAULT '',
  affiliate_id int(11) DEFAULT '0',
  affiliate_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  affiliate_browser varchar(100) DEFAULT NULL,
  affiliate_ipaddress varchar(20) DEFAULT NULL,
  affiliate_clickthroughs_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (txn_signature,orders_id),
  KEY idx_orders_session_info_txn_signature (txn_signature)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: orders_ship_methods
#
CREATE TABLE orders_ship_methods (
  ship_methods_id int(11) NOT NULL auto_increment,
  ship_method_language int(11) NOT NULL DEFAULT '1',
  ship_method_sort smallint(3) unsigned NOT NULL DEFAULT '9999',
  ship_method varchar(255) NOT NULL DEFAULT '',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (ship_methods_id,ship_method_language)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: orders_status
#
CREATE TABLE orders_status (
  orders_status_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  orders_status_name varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (orders_status_id,language_id),
  KEY idx_orders_status_name (orders_status_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: orders_status_history
#
CREATE TABLE orders_status_history (
  orders_status_history_id int(11) NOT NULL auto_increment,
  orders_id int(11) NOT NULL DEFAULT '0',
  orders_status_id int(5) NOT NULL DEFAULT '0',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  customer_notified tinyint(1) NOT NULL DEFAULT '0',
  comments text DEFAULT NULL,
  PRIMARY KEY (orders_status_history_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: orders_total
#
CREATE TABLE orders_total (
  orders_total_id int(10) unsigned NOT NULL auto_increment,
  orders_id int(11) NOT NULL DEFAULT '0',
  title varchar(255) NOT NULL DEFAULT '',
  text varchar(255) NOT NULL DEFAULT '',
  value decimal(15,4) NOT NULL DEFAULT '0.0000',
  class varchar(32) NOT NULL DEFAULT '',
  sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  PRIMARY KEY (orders_total_id),
  KEY idx_orders_total_orders_id (orders_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: packaging
#
CREATE TABLE packaging (
  package_id int(11) NOT NULL auto_increment,
  package_name varchar(64) NOT NULL DEFAULT '',
  package_description varchar(255) NOT NULL DEFAULT '',
  package_length decimal(6,2) NOT NULL DEFAULT '5.00',
  package_width decimal(6,2) NOT NULL DEFAULT '5.00',
  package_height decimal(6,2) NOT NULL DEFAULT '5.00',
  package_empty_weight decimal(6,2) NOT NULL DEFAULT '0.00',
  package_max_weight decimal(6,2) NOT NULL DEFAULT '50.00',
  package_cost decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (package_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: pages
#
CREATE TABLE pages (
  pages_id int(11) NOT NULL auto_increment,
  pages_image varchar(64) DEFAULT NULL,
  pages_date_added timestamp DEFAULT CURRENT_TIMESTAMP,
  pages_date_modified timestamp DEFAULT '0000-00-00 00:00:00',
  pages_author varchar(255) NOT NULL DEFAULT '',
  pages_status tinyint(1) NOT NULL DEFAULT '1',
  pages_sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  pages_in_menu tinyint(1) unsigned NOT NULL DEFAULT '0',
  pages_in_page_listing tinyint(1) unsigned NOT NULL DEFAULT '1',
  pages_url varchar(255) NOT NULL DEFAULT '',
  pages_append_cdpath varchar(64) NOT NULL DEFAULT '',
  pages_url_target varchar(255) NOT NULL DEFAULT '',
  pages_attach_product int(11) NOT NULL DEFAULT '0',
  pages_group_access varchar(64) NOT NULL DEFAULT 'G,0',
  PRIMARY KEY (pages_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: pages_categories
#
CREATE TABLE pages_categories (
  categories_id int(11) NOT NULL auto_increment,
  categories_image varchar(64) DEFAULT NULL,
  categories_sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  categories_date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  categories_last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  categories_status tinyint(1) NOT NULL DEFAULT '0',
  categories_parent_id int(11) NOT NULL DEFAULT '0',
  category_append_cdpath tinyint(1) unsigned NOT NULL DEFAULT '0',
  categories_url_override varchar(255) NOT NULL DEFAULT '',
  categories_url_override_target varchar(255) NOT NULL DEFAULT '',
  category_heading_title_image varchar(64) NOT NULL DEFAULT '',
  category_header_banner varchar(64) NOT NULL DEFAULT '',
  categories_sub_category_view tinyint(3) unsigned NOT NULL DEFAULT '0',
  categories_listing_content_mode tinyint(3) unsigned NOT NULL DEFAULT '0',
  categories_listing_columns tinyint(3) unsigned NOT NULL DEFAULT '0',
  categories_in_menu tinyint(1) unsigned NOT NULL DEFAULT '1',
  categories_in_pages_listing tinyint(1) unsigned NOT NULL DEFAULT '1',
  categories_language_saving_option tinyint(3) unsigned NOT NULL DEFAULT '0',
  categories_template varchar(32) DEFAULT NULL,
  categories_attach_product int(11) NOT NULL DEFAULT '0',
  pages_group_access varchar(64) NOT NULL DEFAULT 'G,0',
  PRIMARY KEY (categories_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: pages_categories_description
#
CREATE TABLE pages_categories_description (
  categories_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  categories_name varchar(255) DEFAULT NULL,
  categories_description text DEFAULT NULL,
  categories_heading varchar(64) NOT NULL DEFAULT '',
  categories_blurb text NOT NULL DEFAULT '',
  categories_tag_keywords varchar(96) NOT NULL DEFAULT '',
  categories_meta_title varchar(96) NOT NULL DEFAULT '',
  categories_meta_keywords text NOT NULL DEFAULT '',
  categories_meta_description text NOT NULL DEFAULT '',
  PRIMARY KEY (categories_id,language_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: pages_description
#
CREATE TABLE pages_description (
  pages_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  pages_title varchar(255) NOT NULL DEFAULT '',
  pages_meta_title varchar(80) DEFAULT NULL,
  pages_meta_keywords varchar(255) DEFAULT NULL,
  pages_meta_description varchar(255) DEFAULT NULL,
  pages_blurb text DEFAULT NULL,
  pages_body text DEFAULT NULL,
  pages_menu_name varchar(64) NOT NULL DEFAULT '',
  pages_file varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (pages_id,language_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: pages_to_categories
#
CREATE TABLE pages_to_categories (
  pages_id int(11) NOT NULL DEFAULT '0',
  categories_id int(11) NOT NULL DEFAULT '0',
  page_sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  PRIMARY KEY (pages_id,categories_id),
  KEY idx_categories_id (categories_id,pages_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: paypal
#
CREATE TABLE paypal (
  paypal_id int(11) NOT NULL auto_increment,
  txn_type varchar(10) NOT NULL DEFAULT '',
  reason_code varchar(15) DEFAULT NULL,
  payment_type varchar(7) NOT NULL DEFAULT '',
  payment_status varchar(17) NOT NULL DEFAULT '',
  pending_reason varchar(14) DEFAULT NULL,
  invoice varchar(64) DEFAULT NULL,
  mc_currency char(3) NOT NULL DEFAULT '',
  first_name varchar(32) NOT NULL DEFAULT '',
  last_name varchar(32) NOT NULL DEFAULT '',
  payer_business_name varchar(64) DEFAULT NULL,
  address_name varchar(32) DEFAULT NULL,
  address_street varchar(64) DEFAULT NULL,
  address_city varchar(32) DEFAULT NULL,
  address_state varchar(32) DEFAULT NULL,
  address_zip varchar(10) DEFAULT NULL,
  address_country varchar(64) DEFAULT NULL,
  address_status varchar(11) DEFAULT NULL,
  payer_email varchar(96) NOT NULL DEFAULT '',
  payer_id varchar(32) NOT NULL DEFAULT '',
  payer_status varchar(10) NOT NULL DEFAULT '',
  payment_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  payment_time_zone varchar(4) NOT NULL DEFAULT '',
  business varchar(96) NOT NULL DEFAULT '',
  receiver_email varchar(96) NOT NULL DEFAULT '',
  receiver_id varchar(32) NOT NULL DEFAULT '',
  txn_id varchar(17) NOT NULL DEFAULT '',
  parent_txn_id varchar(17) DEFAULT NULL,
  num_cart_items tinyint(4) unsigned NOT NULL DEFAULT '1',
  mc_gross decimal(7,2) NOT NULL DEFAULT '0.00',
  mc_fee decimal(7,2) NOT NULL DEFAULT '0.00',
  payment_gross decimal(7,2) DEFAULT NULL,
  payment_fee decimal(7,2) DEFAULT NULL,
  settle_amount decimal(7,2) DEFAULT NULL,
  settle_currency char(3) DEFAULT NULL,
  exchange_rate decimal(4,2) DEFAULT NULL,
  for_auction varchar(5) NOT NULL DEFAULT 'false',
  auction_buyer_id varchar(64) NOT NULL DEFAULT '',
  auction_closing_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  auction_multi_item tinyint(4) NOT NULL DEFAULT '0',
  quantity int(11) NOT NULL DEFAULT '0',
  tax decimal(7,2) DEFAULT NULL,
  notify_version decimal(2,1) NOT NULL DEFAULT '0.0',
  verify_sign varchar(128) NOT NULL DEFAULT '',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  memo text DEFAULT NULL,
  PRIMARY KEY (paypal_id,txn_id),
  KEY idx_paypal_paypal_id (paypal_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: paypal_payment_status_history
#
CREATE TABLE paypal_payment_status_history (
  payment_status_history_id int(11) NOT NULL auto_increment,
  paypal_id int(11) NOT NULL DEFAULT '0',
  payment_status varchar(17) NOT NULL DEFAULT '',
  pending_reason varchar(14) DEFAULT NULL,
  reason_code varchar(15) DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (payment_status_history_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products
#
CREATE TABLE products (
  products_id int(11) NOT NULL auto_increment,
  products_quantity int(4) NOT NULL DEFAULT '0',
  products_model varchar(25) DEFAULT NULL,
  products_image varchar(64) DEFAULT NULL,
  products_image_med varchar(64) DEFAULT NULL,
  products_image_lrg varchar(64) DEFAULT NULL,
  products_image_sm_1 varchar(64) DEFAULT NULL,
  products_image_xl_1 varchar(64) DEFAULT NULL,
  products_image_sm_2 varchar(64) DEFAULT NULL,
  products_image_xl_2 varchar(64) DEFAULT NULL,
  products_image_sm_3 varchar(64) DEFAULT NULL,
  products_image_xl_3 varchar(64) DEFAULT NULL,
  products_image_sm_4 varchar(64) DEFAULT NULL,
  products_image_xl_4 varchar(64) DEFAULT NULL,
  products_image_sm_5 varchar(64) DEFAULT NULL,
  products_image_xl_5 varchar(64) DEFAULT NULL,
  products_image_sm_6 varchar(64) DEFAULT NULL,
  products_image_xl_6 varchar(64) DEFAULT NULL,
  products_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  products_last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  products_date_available datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  products_weight decimal(5,2) NOT NULL DEFAULT '0.00',
  products_status tinyint(1) NOT NULL DEFAULT '0',
  products_tax_class_id int(11) NOT NULL DEFAULT '0',
  manufacturers_id int(11) DEFAULT NULL,
  products_ordered int(11) NOT NULL DEFAULT '0',
  products_parent_id int(11) NOT NULL DEFAULT '0',
  products_price1 decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_price2 decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_price3 decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_price4 decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_price5 decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_price6 decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_price7 decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_price8 decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_price9 decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_price10 decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_price11 decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_price1_qty int(11) NOT NULL DEFAULT '0',
  products_price2_qty int(11) NOT NULL DEFAULT '0',
  products_price3_qty int(11) NOT NULL DEFAULT '0',
  products_price4_qty int(11) NOT NULL DEFAULT '0',
  products_price5_qty int(11) NOT NULL DEFAULT '0',
  products_price6_qty int(11) NOT NULL DEFAULT '0',
  products_price7_qty int(11) NOT NULL DEFAULT '0',
  products_price8_qty int(11) NOT NULL DEFAULT '0',
  products_price9_qty int(11) NOT NULL DEFAULT '0',
  products_price10_qty int(11) NOT NULL DEFAULT '0',
  products_price11_qty int(11) NOT NULL DEFAULT '0',
  products_qty_blocks int(11) NOT NULL DEFAULT '1',
  sort_order smallint(3) NOT NULL DEFAULT '0',
  products_qty_days smallint(4) NOT NULL DEFAULT '0',
  products_qty_years smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (products_id),
  KEY idx_products_date_added (products_date_added)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_attributes
#
CREATE TABLE products_attributes (
  products_attributes_id int(11) NOT NULL auto_increment,
  products_id int(11) NOT NULL DEFAULT '0',
  options_id int(11) NOT NULL DEFAULT '0',
  options_values_id int(11) NOT NULL DEFAULT '0',
  options_values_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  price_prefix char(1) NOT NULL DEFAULT '',
  products_options_sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  PRIMARY KEY (products_attributes_id),
  KEY idx_products_id (products_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_attributes_download
#
CREATE TABLE products_attributes_download (
  products_attributes_id int(11) NOT NULL DEFAULT '0',
  products_attributes_filename varchar(255) NOT NULL DEFAULT '',
  products_attributes_maxdays int(2) DEFAULT '0',
  products_attributes_maxcount int(2) DEFAULT '0',
  PRIMARY KEY (products_attributes_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_description
#
CREATE TABLE products_description (
  products_id int(11) NOT NULL auto_increment,
  language_id int(11) NOT NULL DEFAULT '1',
  products_name varchar(128) NOT NULL DEFAULT '',
  products_blurb text DEFAULT NULL,
  products_description text DEFAULT NULL,
  products_url varchar(255) DEFAULT NULL,
  products_viewed int(5) DEFAULT '0',
  products_head_title_tag varchar(80) DEFAULT NULL,
  products_head_desc_tag longtext NOT NULL DEFAULT '',
  products_head_keywords_tag longtext NOT NULL DEFAULT '',
  PRIMARY KEY (products_id,language_id),
  KEY products_name (products_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_extra_fields
#
CREATE TABLE products_extra_fields (
  products_extra_fields_id int(11) NOT NULL auto_increment,
  products_extra_fields_name varchar(64) NOT NULL DEFAULT '',
  products_extra_fields_order int(3) NOT NULL DEFAULT '0',
  products_extra_fields_status tinyint(1) NOT NULL DEFAULT '0',
  languages_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (products_extra_fields_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_notifications
#
CREATE TABLE products_notifications (
  products_id int(11) NOT NULL DEFAULT '0',
  customers_id int(11) NOT NULL DEFAULT '0',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (products_id,customers_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_options
#
CREATE TABLE products_options (
  products_options_id int(11) NOT NULL DEFAULT '0',
  products_options_sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  options_type int(5) NOT NULL DEFAULT '0',
  options_length smallint(2) NOT NULL DEFAULT '32',
  options_date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  options_last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (products_options_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_options_text
#
CREATE TABLE products_options_text (
  products_options_text_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  products_options_name varchar(32) NOT NULL DEFAULT '',
  products_options_instruct varchar(64) DEFAULT NULL,
  PRIMARY KEY (products_options_text_id,language_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_options_values
#
CREATE TABLE products_options_values (
  products_options_values_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  products_options_values_name varchar(64) NOT NULL DEFAULT '',
  options_values_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (products_options_values_id,language_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_options_values_to_products_options
#
CREATE TABLE products_options_values_to_products_options (
  products_options_values_to_products_options_id int(11) NOT NULL auto_increment,
  products_options_id int(11) NOT NULL DEFAULT '0',
  products_options_values_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (products_options_values_to_products_options_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_to_categories
#
CREATE TABLE products_to_categories (
  products_id int(11) NOT NULL DEFAULT '0',
  categories_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (products_id,categories_id),
  KEY idx_categories_id (categories_id,products_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_to_products_extra_fields
#
CREATE TABLE products_to_products_extra_fields (
  products_id int(11) NOT NULL DEFAULT '0',
  products_extra_fields_id int(11) NOT NULL DEFAULT '0',
  products_extra_fields_value varchar(64) DEFAULT NULL,
  PRIMARY KEY (products_id,products_extra_fields_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: products_xsell
#
CREATE TABLE products_xsell (
  ID int(10) NOT NULL auto_increment,
  products_id int(11) NOT NULL DEFAULT '0',
  xsell_id int(11) NOT NULL DEFAULT '0',
  sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  PRIMARY KEY (ID),
  KEY idx_products_id (products_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: refund_method
#
CREATE TABLE refund_method (
  refund_method_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  refund_method_name varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (refund_method_id,language_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: refund_payments
#
CREATE TABLE refund_payments (
  refund_payment_id int(11) NOT NULL auto_increment,
  returns_id int(11) NOT NULL DEFAULT '0',
  refund_payment_name varchar(64) NOT NULL DEFAULT 'No Refund Made',
  refund_payment_value decimal(15,4) NOT NULL DEFAULT '0.0000',
  refund_payment_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  refund_payment_reference varchar(50) DEFAULT NULL,
  refund_payment_deductions decimal(15,4) NOT NULL DEFAULT '0.0000',
  customer_method varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (refund_payment_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: return_reasons
#
CREATE TABLE return_reasons (
  return_reason_id tinyint(3) unsigned NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  return_reason_name varchar(25) DEFAULT NULL,
  PRIMARY KEY (return_reason_id,language_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: return_text
#
CREATE TABLE return_text (
  return_text_id tinyint(3) unsigned NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  return_text_one text DEFAULT NULL,
  PRIMARY KEY (return_text_id,language_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: returned_products
#
CREATE TABLE returned_products (
  returns_id int(11) NOT NULL auto_increment,
  customers_id int(11) NOT NULL DEFAULT '0',
  rma_value varchar(15) NOT NULL DEFAULT '0',
  order_id int(11) NOT NULL DEFAULT '0',
  customers_name varchar(64) NOT NULL DEFAULT '',
  customers_acct varchar(32) DEFAULT NULL,
  customers_company varchar(32) DEFAULT NULL,
  customers_street_address varchar(64) NOT NULL DEFAULT '',
  customers_suburb varchar(32) DEFAULT NULL,
  customers_city varchar(32) NOT NULL DEFAULT '',
  customers_postcode varchar(10) NOT NULL DEFAULT '',
  customers_state varchar(32) DEFAULT NULL,
  customers_country varchar(32) NOT NULL DEFAULT '',
  customers_telephone varchar(32) NOT NULL DEFAULT '',
  customers_fax varchar(32) NOT NULL DEFAULT '',
  customers_email_address varchar(96) NOT NULL DEFAULT '',
  customers_address_format_id int(5) NOT NULL DEFAULT '0',
  delivery_name varchar(64) NOT NULL DEFAULT '',
  delivery_company varchar(32) DEFAULT NULL,
  delivery_street_address varchar(64) NOT NULL DEFAULT '',
  delivery_suburb varchar(32) DEFAULT NULL,
  delivery_city varchar(32) NOT NULL DEFAULT '',
  delivery_postcode varchar(10) NOT NULL DEFAULT '',
  delivery_state varchar(32) DEFAULT NULL,
  delivery_country varchar(32) NOT NULL DEFAULT '',
  delivery_address_format_id int(5) NOT NULL DEFAULT '0',
  billing_name varchar(64) NOT NULL DEFAULT '',
  billing_acct varchar(32) DEFAULT NULL,
  billing_company varchar(32) DEFAULT NULL,
  billing_street_address varchar(64) NOT NULL DEFAULT '',
  billing_suburb varchar(32) DEFAULT NULL,
  billing_city varchar(32) NOT NULL DEFAULT '',
  billing_postcode varchar(10) NOT NULL DEFAULT '',
  billing_state varchar(32) DEFAULT NULL,
  billing_country varchar(32) NOT NULL DEFAULT '',
  billing_address_format_id int(5) NOT NULL DEFAULT '0',
  payment_method varchar(64) NOT NULL DEFAULT '',
  cc_type varchar(20) DEFAULT NULL,
  cc_owner varchar(64) DEFAULT NULL,
  cc_number varchar(32) DEFAULT NULL,
  cc_expires varchar(4) DEFAULT NULL,
  cvvnumber char(3) DEFAULT NULL,
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_purchased datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  returns_status int(5) NOT NULL DEFAULT '1',
  returns_date_finished datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  comments text DEFAULT NULL,
  currency char(3) DEFAULT NULL,
  currency_value decimal(14,6) DEFAULT NULL,
  account_name varchar(32) NOT NULL DEFAULT '',
  account_number varchar(20) DEFAULT NULL,
  po_number varchar(12) DEFAULT NULL,
  date_finished datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  returns_reason tinyint(3) unsigned DEFAULT '0',
  contact_user_name varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (returns_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: returns_products_attributes_data
#
CREATE TABLE returns_products_attributes_data (
  returns_products_attributes_id int(11) NOT NULL auto_increment,
  returns_id int(11) NOT NULL DEFAULT '0',
  returns_products_id int(11) NOT NULL DEFAULT '0',
  products_options varchar(32) NOT NULL DEFAULT '',
  products_options_values varchar(64) NOT NULL DEFAULT '',
  options_values_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  price_prefix char(1) NOT NULL DEFAULT '',
  products_options_id int(11) NOT NULL DEFAULT '0',
  products_options_values_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (returns_products_attributes_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: returns_products_data
#
CREATE TABLE returns_products_data (
  returns_products_id int(11) NOT NULL auto_increment,
  returns_id int(11) NOT NULL DEFAULT '0',
  order_id int(11) NOT NULL DEFAULT '0',
  products_id int(11) NOT NULL DEFAULT '0',
  products_model varchar(25) DEFAULT NULL,
  products_name varchar(128) NOT NULL DEFAULT '',
  products_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_discount_made decimal(4,2) DEFAULT NULL,
  final_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_tax decimal(7,4) NOT NULL DEFAULT '0.0000',
  products_quantity int(2) NOT NULL DEFAULT '0',
  products_serial_number varchar(128) DEFAULT NULL,
  products_returned tinyint(2) unsigned DEFAULT '0',
  products_exchanged tinyint(2) unsigned DEFAULT '0',
  PRIMARY KEY (returns_products_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: returns_status
#
CREATE TABLE returns_status (
  returns_status_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  returns_status_name varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (returns_status_id,language_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: returns_status_history
#
CREATE TABLE returns_status_history (
  returns_status_history_id int(11) unsigned NOT NULL auto_increment,
  returns_id int(11) NOT NULL DEFAULT '0',
  returns_status int(11) NOT NULL DEFAULT '0',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  customer_notified tinyint(1) unsigned DEFAULT '0',
  comments text DEFAULT NULL,
  PRIMARY KEY (returns_status_history_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: returns_total
#
CREATE TABLE returns_total (
  returns_total_id int(10) unsigned NOT NULL auto_increment,
  returns_id int(11) NOT NULL DEFAULT '0',
  title varchar(255) NOT NULL DEFAULT '',
  text varchar(255) NOT NULL DEFAULT '',
  value decimal(15,4) NOT NULL DEFAULT '0.0000',
  class varchar(32) NOT NULL DEFAULT '',
  sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  PRIMARY KEY (returns_total_id),
  KEY idx_returns_total_returns_id (returns_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: reviews
#
CREATE TABLE reviews (
  reviews_id int(11) NOT NULL auto_increment,
  products_id int(11) NOT NULL DEFAULT '0',
  customers_id int(11) DEFAULT NULL,
  customers_name varchar(64) NOT NULL DEFAULT '',
  reviews_rating int(1) DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  reviews_read int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (reviews_id),
  KEY idx_products_id (products_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: reviews_description
#
CREATE TABLE reviews_description (
  reviews_id int(11) NOT NULL DEFAULT '0',
  languages_id int(11) NOT NULL DEFAULT '0',
  reviews_text text NOT NULL DEFAULT '',
  PRIMARY KEY (reviews_id,languages_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: sessions
#
CREATE TABLE sessions (
  sesskey varchar(32) NOT NULL DEFAULT '',
  expiry int(11) unsigned NOT NULL DEFAULT '0',
  value mediumtext NOT NULL DEFAULT '',
  PRIMARY KEY (sesskey)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: specials
#
CREATE TABLE specials (
  specials_id int(11) NOT NULL auto_increment,
  products_id int(11) NOT NULL DEFAULT '0',
  specials_new_products_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  specials_date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  specials_last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  expires_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_status_change datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  status tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (specials_id),
  KEY idx_products_id (products_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: tax_class
#
CREATE TABLE tax_class (
  tax_class_id int(11) NOT NULL auto_increment,
  tax_class_title varchar(32) NOT NULL DEFAULT '',
  tax_class_description varchar(255) NOT NULL DEFAULT '',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (tax_class_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: tax_rates
#
CREATE TABLE tax_rates (
  tax_rates_id int(11) NOT NULL auto_increment,
  tax_zone_id int(11) NOT NULL DEFAULT '0',
  tax_class_id int(11) NOT NULL DEFAULT '0',
  tax_priority int(5) DEFAULT '1',
  tax_rate decimal(7,4) NOT NULL DEFAULT '0.0000',
  tax_description varchar(255) NOT NULL DEFAULT '',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (tax_rates_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: template
#
CREATE TABLE template (
  template_id int(11) NOT NULL auto_increment,
  template_name varchar(64) NOT NULL DEFAULT '',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  template_image varchar(64) DEFAULT NULL,
  template_cellpadding_main char(3) NOT NULL DEFAULT '0',
  template_cellpadding_sub char(3) NOT NULL DEFAULT '0',
  template_cellpadding_left char(3) NOT NULL DEFAULT '0',
  template_cellpadding_right char(3) NOT NULL DEFAULT '0',
  site_width varchar(5) NOT NULL DEFAULT '100%',
  include_column_left varchar(64) NOT NULL DEFAULT 'yes',
  include_column_right varchar(64) NOT NULL DEFAULT 'yes',
  box_width_left varchar(4) NOT NULL DEFAULT '125',
  box_width_right varchar(4) NOT NULL DEFAULT '125',
  main_table_border varchar(6) NOT NULL DEFAULT 'no',
  active char(1) NOT NULL DEFAULT '1',
  show_heading_title_original varchar(6) NOT NULL DEFAULT 'yes',
  languages_in_header char(3) DEFAULT 'no',
  cart_in_header char(3) NOT NULL DEFAULT 'no',
  show_header_link_buttons char(3) NOT NULL DEFAULT 'no',
  module_one varchar(64) NOT NULL DEFAULT '',
  module_two varchar(64) NOT NULL DEFAULT '',
  module_three varchar(64) NOT NULL DEFAULT '',
  module_four varchar(64) NOT NULL DEFAULT '',
  module_five varchar(64) NOT NULL DEFAULT '',
  module_six varchar(64) NOT NULL DEFAULT '',
  customer_greeting char(3) NOT NULL DEFAULT 'yes',
  edit_customer_greeting_personal text NOT NULL DEFAULT '',
  edit_customer_greeting_personal_relogon text NOT NULL DEFAULT '',
  edit_greeting_guest text NOT NULL DEFAULT '',
  side_box_left_width int(10) DEFAULT '1',
  side_box_right_width int(10) DEFAULT '1',
  PRIMARY KEY (template_id),
  KEY IDX_TEMPLATE_NAME (template_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: topics
#
CREATE TABLE topics (
  topics_id int(11) NOT NULL auto_increment,
  topics_image varchar(64) DEFAULT NULL,
  parent_id int(11) NOT NULL DEFAULT '0',
  sort_order smallint(3) unsigned NOT NULL DEFAULT '9999',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (topics_id),
  KEY idx_topics_parent_id (parent_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: topics_description
#
CREATE TABLE topics_description (
  topics_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  topics_name varchar(64) NOT NULL DEFAULT '',
  topics_heading_title varchar(64) DEFAULT NULL,
  topics_description text DEFAULT NULL,
  PRIMARY KEY (topics_id,language_id),
  KEY idx_topics_name (topics_name)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: transaction_log
#
CREATE TABLE transaction_log (
  log_id int(11) NOT NULL auto_increment,
  token varchar(128) NOT NULL DEFAULT '',
  transaction_id varchar(64) NOT NULL DEFAULT '',
  order_id int(11) NOT NULL DEFAULT '0',
  created_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (log_id,order_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: visual_verify_code
#
CREATE TABLE visual_verify_code (
  oscsid varchar(32) NOT NULL DEFAULT '',
  code varchar(6) NOT NULL DEFAULT '',
  dt timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (oscsid)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: whos_online
#
CREATE TABLE whos_online (
  customer_id int(11) DEFAULT NULL,
  full_name varchar(64) NOT NULL DEFAULT '',
  session_id varchar(128) NOT NULL DEFAULT '',
  ip_address varchar(15) NOT NULL DEFAULT '',
  time_entry varchar(14) NOT NULL DEFAULT '',
  time_last_click varchar(14) NOT NULL DEFAULT '',
  last_page_url varchar(255) NOT NULL DEFAULT '',
  http_referer varchar(255) NOT NULL DEFAULT '',
  user_agent varchar(255) NOT NULL DEFAULT '',
  KEY idx_customer_ID (customer_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: zones
#
CREATE TABLE zones (
  zone_id int(11) NOT NULL auto_increment,
  zone_country_id int(11) NOT NULL DEFAULT '0',
  zone_code varchar(32) NOT NULL DEFAULT '',
  zone_name varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (zone_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


#
# Table structure for: zones_to_geo_zones
#
CREATE TABLE zones_to_geo_zones (
  association_id int(11) NOT NULL auto_increment,
  zone_country_id int(11) NOT NULL DEFAULT '0',
  zone_id int(11) DEFAULT NULL,
  geo_zone_id int(11) DEFAULT NULL,
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (association_id)
) ENGINE=MyISAM DEFAULT COLLATE=latin1_swedish_ci;


