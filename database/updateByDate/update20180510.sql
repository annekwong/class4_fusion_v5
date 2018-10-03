alter table system_parameter add column enable_client_download_rate character varying default false;
alter table system_parameter add column enable_client_delete_trunk character varying default false;
alter table system_parameter add column enable_client_disable_trunk character varying default false;
UPDATE version_information SET major_ver = 'V5.0.0', build_date = '2018-05-10' WHERE program_name = 'dnl_softswitch';
UPDATE version_information SET major_ver = 'V5.2.20180510', build_date = '2018-05-10' WHERE program_name = 'database_version';