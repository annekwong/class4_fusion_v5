ALTER TABLE client ADD COLUMN did_invoice_include varchar(255);

alter table client_record add column did_invoice_include varchar(255);
alter table client_record add column time_new numeric;
alter table client_record add column flag_new character(1);
alter table client_record add column record_id_new integer not null default nextval('client_record_record_id_seq'::regclass);
update client_record set time_new = time;
update client_record set flag_new = flag;
update client_record set record_id_new = record_id;
alter table client_record drop column time;
alter table client_record drop column flag;
alter table client_record drop column record_id;
alter table client_record rename column time_new to time;
alter table client_record rename column flag_new to flag;
alter table client_record rename column record_id_new to record_id;