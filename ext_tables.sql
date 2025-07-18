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

    ad_storage smallint(5) unsigned DEFAULT '0' NOT NULL,
    analytics_storage smallint(5) unsigned DEFAULT '0' NOT NULL,
    ad_user_data smallint(5) unsigned DEFAULT '0' NOT NULL,
    ad_personalization smallint(5) unsigned DEFAULT '0' NOT NULL,
    functionality_storage smallint(5) unsigned DEFAULT '0' NOT NULL,
    personalization_storage smallint(5) unsigned DEFAULT '0' NOT NULL,
    security_storage smallint(5) unsigned DEFAULT '0' NOT NULL,

	categories int(11) unsigned DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_wecookieconsent_domain_model_cookie'
#
CREATE TABLE tx_wecookieconsent_domain_model_cookie (
	title varchar(255) DEFAULT '' NOT NULL,
	regex varchar(255) DEFAULT '' NOT NULL,
	description text,
	max_age varchar(255) DEFAULT '' NOT NULL,

	service int(11) unsigned DEFAULT '0' NOT NULL
);