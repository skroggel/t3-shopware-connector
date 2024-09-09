CREATE TABLE tx_shopwareconnector_domain_model_category
(
	uid          int(11) NOT NULL auto_increment,
	pid          int(11) DEFAULT '0' NOT NULL,

	hidden       tinyint(1) DEFAULT '0' NOT NULL,
	crdate       int(11) DEFAULT '0' NOT NULL,
	tstamp       int(11) DEFAULT '0' NOT NULL,

	sw_id  varchar(255) NOT NULL DEFAULT '',
	sw_language_id      varchar(255) NOT NULL DEFAULT '',
	parent_uid   int(11) DEFAULT '0' NOT NULL,
	checksum     varchar(255) NOT NULL DEFAULT '',

	name         varchar(255) NOT NULL DEFAULT '',
	description  varchar(255) NOT NULL DEFAULT '',
	slug         varchar(255) NOT NULL DEFAULT '',
	hide_in_menu tinyint(1) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY          parent (pid),
	KEY          sw_id (sw_id),
	UNIQUE KEY unique_category (sw_id, sw_language_id)
);


CREATE TABLE tx_shopwareconnector_domain_model_media
(
	uid            int(11) NOT NULL auto_increment,
	pid            int(11) DEFAULT '0' NOT NULL,
	crdate         int(11) DEFAULT '0' NOT NULL,
	tstamp         int(11) DEFAULT '0' NOT NULL,

	sw_id       varchar(255) NOT NULL DEFAULT '',
	sw_language_id      varchar(255) NOT NULL DEFAULT '',
	checksum       varchar(255) NOT NULL DEFAULT '',

	mime_type      varchar(255) NOT NULL DEFAULT '',
	file_name      varchar(255) NOT NULL DEFAULT '',
	file_extension varchar(255) NOT NULL DEFAULT '',
	file_size      int(11) DEFAULT '0' NOT NULL,
	title          varchar(255) NOT NULL DEFAULT '',
	alternative    varchar(255) NOT NULL DEFAULT '',
	url            varchar(255) NOT NULL DEFAULT '',
	sorting        int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY            parent (pid),
	KEY            sw_id (sw_id),
	UNIQUE KEY unique_media (sw_id, sw_language_id)
);


CREATE TABLE tx_shopwareconnector_domain_model_product
(
	uid              int(11) NOT NULL auto_increment,
	pid              int(11) DEFAULT '0' NOT NULL,
	crdate           int(11) DEFAULT '0' NOT NULL,
	tstamp           int(11) DEFAULT '0' NOT NULL,
	hidden           tinyint(1) DEFAULT '0' NOT NULL,

	sw_id      varchar(255) NOT NULL DEFAULT '',
	sw_language_id      varchar(255) NOT NULL DEFAULT '',
	sw_manufacturer_id  varchar(255) NOT NULL DEFAULT '',
	parent_uid       int(11) DEFAULT '0' NOT NULL,
	checksum         varchar(255) NOT NULL DEFAULT '',

	product_number   varchar(255) NOT NULL DEFAULT '',
	name             varchar(255) NOT NULL DEFAULT '',
	ean              varchar(255) NOT NULL DEFAULT '',
	is_new           tinyint(1) DEFAULT '0' NOT NULL,
	custom_fields    text                  DEFAULT '',
	release_date     int(11) DEFAULT '0' NOT NULL,

	price            decimal(10, 2)        DEFAULT '0.00' NOT NULL,
	calculated_price text                  DEFAULT '',

	available_stock  int(11) DEFAULT '0' NOT NULL,
	stock            int(11) DEFAULT '0' NOT NULL,
	available        tinyint(1) DEFAULT '0' NOT NULL,
	restock_time     int(11) DEFAULT '0' NOT NULL,
	shipping_free    tinyint(1) DEFAULT '0' NOT NULL,
	delivery_time    text                  DEFAULT '',
	purchase_steps   int(11) DEFAULT '0' NOT NULL,
	max_purchase     int(11) DEFAULT '0' NOT NULL,
	min_purchase     int(11) DEFAULT '0' NOT NULL,
	purchase_unit    varchar(255) NOT NULL DEFAULT '',
	pack_unit        varchar(255) NOT NULL DEFAULT '',
	pack_unit_plural varchar(255) NOT NULL DEFAULT '',

	weight           decimal(10, 2)        DEFAULT '0.00' NOT NULL,
	width            decimal(10, 2)        DEFAULT '0.00' NOT NULL,
	height           decimal(10, 2)        DEFAULT '0.00' NOT NULL,
	length           decimal(10, 2)        DEFAULT '0.00' NOT NULL,

	tags             text                  DEFAULT '',
	description      varchar(255) NOT NULL DEFAULT '',
	cover            varchar(255) NOT NULL DEFAULT '',
	cover_mime_type  varchar(255) NOT NULL DEFAULT '',
	meta_title       varchar(255) NOT NULL DEFAULT '',
	meta_description varchar(255) NOT NULL DEFAULT '',
	meta_keywords    varchar(255) NOT NULL DEFAULT '',

	categories     int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY              parent (pid),
	KEY              sw_id (sw_id),
	UNIQUE KEY unique_product (sw_id, sw_language_id)

);


CREATE TABLE tx_shopwareconnector_domain_model_property
(
	uid         int(11) NOT NULL auto_increment,
	pid         int(11) DEFAULT '0' NOT NULL,
	crdate      int(11) DEFAULT '0' NOT NULL,
	tstamp      int(11) DEFAULT '0' NOT NULL,

	sw_id varchar(255) NOT NULL DEFAULT '',
	sw_language_id      varchar(255) NOT NULL DEFAULT '',
	product_uid varchar(255) NOT NULL DEFAULT '',
	checksum    varchar(255)          DEFAULT '' NOT NULL,

	-- Property Informationen
	name        varchar(255) NOT NULL DEFAULT '',
	value       varchar(255) NOT NULL DEFAULT '',
	group_name  varchar(255) NOT NULL DEFAULT '',
	sorting     int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY         parent (pid),
	KEY         product_uid (product_uid),
	UNIQUE KEY unique_property (product_uid, sw_id, sw_language_id)

);

CREATE TABLE tx_shopwareconnector_product_category_mm
(
	uid_local   int(11) NOT NULL,
	uid_foreign int(11) NOT NULL,
	sorting     int(11) DEFAULT '0' NOT NULL,
	KEY         uid_local (uid_local),
	KEY         uid_foreign (uid_foreign)
);

CREATE TABLE tx_shopwareconnector_product_downloads_mm
(
	uid_local   int(11) NOT NULL,
	uid_foreign int(11) NOT NULL,
	sorting     int(11) DEFAULT '0' NOT NULL,
	KEY         uid_local (uid_local),
	KEY         uid_foreign (uid_foreign)
);
