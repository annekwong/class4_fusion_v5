ALTER TABLE rate_bot_import_logs DROP COLUMN mail_client;
alter table rate_send_rules add column blocked boolean default false;
UPDATE version_information SET major_ver = 'V5.0.0', build_date = '2018-03-08' WHERE program_name = 'dnl_softswitch';
UPDATE version_information SET major_ver = 'V5.2.20180308', build_date = '2018-03-08' WHERE program_name = 'database_version';