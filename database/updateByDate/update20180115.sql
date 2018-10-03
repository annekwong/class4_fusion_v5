alter table resource add column tech_prefix varchar(255);

alter table resource_record add column tech_prefix varchar(255);
alter table resource_record add column time_new numeric;
alter table resource_record add column flag_new character(1);
alter table resource_record add column record_id_new integer not null default nextval('resource_record_record_id_seq'::regclass);
update resource_record set time_new = time;
update resource_record set flag_new = flag;
update resource_record set record_id_new = record_id;
alter table resource_record drop column time;
alter table resource_record drop column flag;
alter table resource_record drop column record_id;
alter table resource_record rename column time_new to time;
alter table resource_record rename column flag_new to flag;
alter table resource_record rename column record_id_new to record_id;