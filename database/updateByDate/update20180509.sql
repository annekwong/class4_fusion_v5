alter table system_parameter add column allow_registration character varying default true;
UPDATE version_information SET major_ver = 'V5.0.0', build_date = '2018-05-09' WHERE program_name = 'dnl_softswitch';
UPDATE version_information SET major_ver = 'V5.2.20180509', build_date = '2018-05-09' WHERE program_name = 'database_version';