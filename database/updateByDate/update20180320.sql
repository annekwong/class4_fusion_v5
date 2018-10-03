alter table resource_template add column t38 boolean DEFAULT false NOT NULL;
UPDATE version_information SET major_ver = 'V5.0.0', build_date = '2018-03-20' WHERE program_name = 'dnl_softswitch';
UPDATE version_information SET major_ver = 'V5.2.20180320', build_date = '2018-03-20' WHERE program_name = 'database_version';