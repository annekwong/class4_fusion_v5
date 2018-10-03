alter table rate_upload_task add column start_from integer default 1;
update version_information set major_ver='V5.2.20171005' where id IN(
       SELECT max(id) FROM version_information
)