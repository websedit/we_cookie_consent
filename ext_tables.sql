#
# Table structure for table 'tx_wecookieconsent_domain_model_service'
#
CREATE TABLE tx_wecookieconsent_domain_model_service (
	provider varchar(255) DEFAULT '' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	description text,
	purpose varchar(255) DEFAULT '' NOT NULL,
	state smallint(5) unsigned DEFAULT '0' NOT NULL,
	preselected smallint(5) unsigned DEFAULT '0' NOT NULL,
	required smallint(5) unsigned DEFAULT '0' NOT NULL,
	opt_out smallint(5) unsigned DEFAULT '0' NOT NULL,
	only_once smallint(5) unsigned DEFAULT '0' NOT NULL,
	#contextual_consent_only smallint(5) unsigned DEFAULT '0' NOT NULL,
	snippet text,
	callback varchar(255) DEFAULT '' NOT NULL,
    domain varchar(255) DEFAULT '' NOT NULL,
	api_key varchar(255) DEFAULT '' NOT NULL,
	gtm_tag_title varchar(255) DEFAULT '' NOT NULL,
	gtm_trigger_title varchar(255) DEFAULT '' NOT NULL,
	gtm_trigger_name varchar(255) DEFAULT '' NOT NULL,
	gtm_variable_title varchar(255) DEFAULT '' NOT NULL,
	gtm_variable_name varchar(255) DEFAULT '' NOT NULL,
	cookies int(11) unsigned DEFAULT '0' NOT NULL,

	categories int(11) unsigned DEFAULT '0' NOT NULL,
	
	#### TYPO3 7/8LTS compatibility ###
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) unsigned DEFAULT '0' NOT NULL,
	t3ver_id int(11) unsigned DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) unsigned DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state smallint(6) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) unsigned DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) unsigned DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	l10n_state text,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	#KEY language (l10n_parent,sys_language_uid)
);

#
# Table structure for table 'tx_wecookieconsent_domain_model_cookie'
#
CREATE TABLE tx_wecookieconsent_domain_model_cookie (
	title varchar(255) DEFAULT '' NOT NULL,
	regex varchar(255) DEFAULT '' NOT NULL,
	description text,
	max_age varchar(255) DEFAULT '' NOT NULL,

	service int(11) unsigned DEFAULT '0' NOT NULL,
	
	#### TYPO3 7/8LTS compatibility ###
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) unsigned DEFAULT '0' NOT NULL,
	t3ver_id int(11) unsigned DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) unsigned DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state smallint(6) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) unsigned DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) unsigned DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	l10n_state text,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	#KEY language (l10n_parent,sys_language_uid)
);