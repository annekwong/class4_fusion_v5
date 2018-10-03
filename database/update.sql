--allowed_sendto_ip trigger--
drop trigger class4_trig_record_allowed_sendto_ip on allowed_sendto_ip;
drop table allowed_sendto_ip_record;
DROP FUNCTION class4_trigfun_record_allowed_sendto_ip();
DROP SEQUENCE allowed_sendto_ip_record_record_id_seq;
SELECT * INTO allowed_sendto_ip_record FROM allowed_sendto_ip where false;
alter table allowed_sendto_ip_record add time numeric;
alter table allowed_sendto_ip_record add flag character(1);
alter table allowed_sendto_ip_record add record_id integer;
alter table allowed_sendto_ip_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE allowed_sendto_ip_record_record_id_seq;
alter table allowed_sendto_ip_record alter COLUMN record_id SET DEFAULT nextval('allowed_sendto_ip_record_record_id_seq'::regclass);

create function class4_trigfun_record_allowed_sendto_ip() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into allowed_sendto_ip_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into allowed_sendto_ip_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into allowed_sendto_ip_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into allowed_sendto_ip_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_allowed_sendto_ip after update or insert or delete on allowed_sendto_ip for each row execute procedure class4_trigfun_record_allowed_sendto_ip();

--client trigger--
drop trigger class4_trig_record_client on client;
drop table client_record;
DROP FUNCTION class4_trigfun_record_client();
DROP SEQUENCE client_record_record_id_seq;
SELECT * INTO client_record FROM client where false;
alter table client_record add time numeric;
alter table client_record add flag character(1);
alter table client_record add record_id integer;
alter table client_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE client_record_record_id_seq;
alter table client_record alter COLUMN record_id SET DEFAULT nextval('client_record_record_id_seq'::regclass);

create function class4_trigfun_record_client() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into client_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into client_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into client_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into client_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_client after update or insert or delete on client for each row execute procedure class4_trigfun_record_client();

--code trigger--
drop trigger class4_trig_record_code on code;
drop table code_record;
DROP FUNCTION class4_trigfun_record_code();
DROP SEQUENCE code_record_record_id_seq;
SELECT * INTO code_record FROM code where false;
alter table code_record add time numeric;
alter table code_record add flag character(1);
alter table code_record add record_id integer;
alter table code_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE code_record_record_id_seq;
alter table code_record alter COLUMN record_id SET DEFAULT nextval('code_record_record_id_seq'::regclass);

create function class4_trigfun_record_code() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into code_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into code_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into code_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into code_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_code after update or insert or delete on code for each row execute procedure class4_trigfun_record_code();

--currency_updates trigger--
drop trigger class4_trig_record_currency_updates on currency_updates;
drop table currency_updates_record;
DROP FUNCTION class4_trigfun_record_currency_updates();
DROP SEQUENCE currency_updates_record_record_id_seq;
SELECT * INTO currency_updates_record FROM currency_updates where false;
alter table currency_updates_record add time numeric;
alter table currency_updates_record add flag character(1);
alter table currency_updates_record add record_id integer;
alter table currency_updates_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE currency_updates_record_record_id_seq;
alter table currency_updates_record alter COLUMN record_id SET DEFAULT nextval('currency_updates_record_record_id_seq'::regclass);

create function class4_trigfun_record_currency_updates() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into currency_updates_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into currency_updates_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into currency_updates_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into currency_updates_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_currency_updates after update or insert or delete on currency_updates for each row execute procedure class4_trigfun_record_currency_updates();

--dynamic_route trigger--
drop trigger class4_trig_record_dynamic_route on dynamic_route;
drop table dynamic_route_record;
DROP FUNCTION class4_trigfun_record_dynamic_route();
DROP SEQUENCE dynamic_route_record_record_id_seq;
SELECT * INTO dynamic_route_record FROM dynamic_route where false;
alter table dynamic_route_record add time numeric;
alter table dynamic_route_record add flag character(1);
alter table dynamic_route_record add record_id integer;
alter table dynamic_route_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE dynamic_route_record_record_id_seq;
alter table dynamic_route_record alter COLUMN record_id SET DEFAULT nextval('dynamic_route_record_record_id_seq'::regclass);

create function class4_trigfun_record_dynamic_route() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into dynamic_route_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into dynamic_route_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into dynamic_route_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into dynamic_route_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_dynamic_route after update or insert or delete on dynamic_route for each row execute procedure class4_trigfun_record_dynamic_route();

--dynamic_route_items trigger--
drop trigger class4_trig_record_dynamic_route_items on dynamic_route_items;
drop table dynamic_route_items_record;
DROP FUNCTION class4_trigfun_record_dynamic_route_items();
DROP SEQUENCE dynamic_route_items_record_record_id_seq;
SELECT * INTO dynamic_route_items_record FROM dynamic_route_items where false;
alter table dynamic_route_items_record add time numeric;
alter table dynamic_route_items_record add flag character(1);
alter table dynamic_route_items_record add record_id integer;
alter table dynamic_route_items_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE dynamic_route_items_record_record_id_seq;
alter table dynamic_route_items_record alter COLUMN record_id SET DEFAULT nextval('dynamic_route_items_record_record_id_seq'::regclass);

create function class4_trigfun_record_dynamic_route_items() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into dynamic_route_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into dynamic_route_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into dynamic_route_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into dynamic_route_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_dynamic_route_items after update or insert or delete on dynamic_route_items for each row execute procedure class4_trigfun_record_dynamic_route_items();

--dynamic_route_override trigger--
drop trigger class4_trig_record_dynamic_route_override on dynamic_route_override;
drop table dynamic_route_override_record;
DROP FUNCTION class4_trigfun_record_dynamic_route_override();
DROP SEQUENCE dynamic_route_override_record_record_id_seq;
SELECT * INTO dynamic_route_override_record FROM dynamic_route_override where false;
alter table dynamic_route_override_record add time numeric;
alter table dynamic_route_override_record add flag character(1);
alter table dynamic_route_override_record add record_id integer;
alter table dynamic_route_override_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE dynamic_route_override_record_record_id_seq;
alter table dynamic_route_override_record alter COLUMN record_id SET DEFAULT nextval('dynamic_route_override_record_record_id_seq'::regclass);

create function class4_trigfun_record_dynamic_route_override() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into dynamic_route_override_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into dynamic_route_override_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into dynamic_route_override_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into dynamic_route_override_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_dynamic_route_override after update or insert or delete on dynamic_route_override for each row execute procedure class4_trigfun_record_dynamic_route_override();

--dynamic_route_pri trigger--
drop trigger class4_trig_record_dynamic_route_pri on dynamic_route_pri;
drop table dynamic_route_pri_record;
DROP FUNCTION class4_trigfun_record_dynamic_route_pri();
DROP SEQUENCE dynamic_route_pri_record_record_id_seq;
SELECT * INTO dynamic_route_pri_record FROM dynamic_route_pri where false;
alter table dynamic_route_pri_record add time numeric;
alter table dynamic_route_pri_record add flag character(1);
alter table dynamic_route_pri_record add record_id integer;
alter table dynamic_route_pri_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE dynamic_route_pri_record_record_id_seq;
alter table dynamic_route_pri_record alter COLUMN record_id SET DEFAULT nextval('dynamic_route_pri_record_record_id_seq'::regclass);

create function class4_trigfun_record_dynamic_route_pri() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into dynamic_route_pri_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into dynamic_route_pri_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into dynamic_route_pri_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into dynamic_route_pri_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_dynamic_route_pri after update or insert or delete on dynamic_route_pri for each row execute procedure class4_trigfun_record_dynamic_route_pri();

--dynamic_route_qos trigger--
drop trigger class4_trig_record_dynamic_route_qos on dynamic_route_qos;
drop table dynamic_route_qos_record;
DROP FUNCTION class4_trigfun_record_dynamic_route_qos();
DROP SEQUENCE dynamic_route_qos_record_record_id_seq;
SELECT * INTO dynamic_route_qos_record FROM dynamic_route_qos where false;
alter table dynamic_route_qos_record add time numeric;
alter table dynamic_route_qos_record add flag character(1);
alter table dynamic_route_qos_record add record_id integer;
alter table dynamic_route_qos_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE dynamic_route_qos_record_record_id_seq;
alter table dynamic_route_qos_record alter COLUMN record_id SET DEFAULT nextval('dynamic_route_qos_record_record_id_seq'::regclass);

create function class4_trigfun_record_dynamic_route_qos() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into dynamic_route_qos_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into dynamic_route_qos_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into dynamic_route_qos_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into dynamic_route_qos_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_dynamic_route_qos after update or insert or delete on dynamic_route_qos for each row execute procedure class4_trigfun_record_dynamic_route_qos();

--egress_profile trigger--
drop trigger class4_trig_record_egress_profile on egress_profile;
drop table egress_profile_record;
DROP FUNCTION class4_trigfun_record_egress_profile();
DROP SEQUENCE egress_profile_record_record_id_seq;
SELECT * INTO egress_profile_record FROM egress_profile where false;
alter table egress_profile_record add time numeric;
alter table egress_profile_record add flag character(1);
alter table egress_profile_record add record_id integer;
alter table egress_profile_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE egress_profile_record_record_id_seq;
alter table egress_profile_record alter COLUMN record_id SET DEFAULT nextval('egress_profile_record_record_id_seq'::regclass);

create function class4_trigfun_record_egress_profile() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into egress_profile_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into egress_profile_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into egress_profile_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into egress_profile_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_egress_profile after update or insert or delete on egress_profile for each row execute procedure class4_trigfun_record_egress_profile();

--exchange_par_account trigger--
drop trigger class4_trig_record_exchange_par_account on exchange_par_account;
drop table exchange_par_account_record;
DROP FUNCTION class4_trigfun_record_exchange_par_account();
DROP SEQUENCE exchange_par_account_record_record_id_seq;
SELECT * INTO exchange_par_account_record FROM exchange_par_account where false;
alter table exchange_par_account_record add time numeric;
alter table exchange_par_account_record add flag character(1);
alter table exchange_par_account_record add record_id integer;
alter table exchange_par_account_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE exchange_par_account_record_record_id_seq;
alter table exchange_par_account_record alter COLUMN record_id SET DEFAULT nextval('exchange_par_account_record_record_id_seq'::regclass);

create function class4_trigfun_record_exchange_par_account() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into exchange_par_account_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into exchange_par_account_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into exchange_par_account_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into exchange_par_account_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_exchange_par_account after update or insert or delete on exchange_par_account for each row execute procedure class4_trigfun_record_exchange_par_account();

--global_route_error trigger--
drop trigger class4_trig_record_global_route_error on global_route_error;
drop table global_route_error_record;
DROP FUNCTION class4_trigfun_record_global_route_error();
DROP SEQUENCE global_route_error_record_record_id_seq;
SELECT * INTO global_route_error_record FROM global_route_error where false;
alter table global_route_error_record add time numeric;
alter table global_route_error_record add flag character(1);
alter table global_route_error_record add record_id integer;
alter table global_route_error_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE global_route_error_record_record_id_seq;
alter table global_route_error_record alter COLUMN record_id SET DEFAULT nextval('global_route_error_record_record_id_seq'::regclass);

create function class4_trigfun_record_global_route_error() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into global_route_error_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into global_route_error_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into global_route_error_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into global_route_error_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_global_route_error after update or insert or delete on global_route_error for each row execute procedure class4_trigfun_record_global_route_error();

--jurisdiction_prefix trigger--
drop trigger class4_trig_record_jurisdiction_prefix on jurisdiction_prefix;
drop table jurisdiction_prefix_record;
DROP FUNCTION class4_trigfun_record_jurisdiction_prefix();
DROP SEQUENCE jurisdiction_prefix_record_record_id_seq;
SELECT * INTO jurisdiction_prefix_record FROM jurisdiction_prefix where false;
alter table jurisdiction_prefix_record add time numeric;
alter table jurisdiction_prefix_record add flag character(1);
alter table jurisdiction_prefix_record add record_id integer;
alter table jurisdiction_prefix_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE jurisdiction_prefix_record_record_id_seq;
alter table jurisdiction_prefix_record alter COLUMN record_id SET DEFAULT nextval('jurisdiction_prefix_record_record_id_seq'::regclass);

create function class4_trigfun_record_jurisdiction_prefix() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into jurisdiction_prefix_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into jurisdiction_prefix_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into jurisdiction_prefix_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into jurisdiction_prefix_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_jurisdiction_prefix after update or insert or delete on jurisdiction_prefix for each row execute procedure class4_trigfun_record_jurisdiction_prefix();

--origination_global_failover trigger--
drop trigger class4_trig_record_origination_global_failover on origination_global_failover;
drop table origination_global_failover_record;
DROP FUNCTION class4_trigfun_record_origination_global_failover();
DROP SEQUENCE origination_global_failover_record_record_id_seq;
SELECT * INTO origination_global_failover_record FROM origination_global_failover where false;
alter table origination_global_failover_record add time numeric;
alter table origination_global_failover_record add flag character(1);
alter table origination_global_failover_record add record_id integer;
alter table origination_global_failover_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE origination_global_failover_record_record_id_seq;
alter table origination_global_failover_record alter COLUMN record_id SET DEFAULT nextval('origination_global_failover_record_record_id_seq'::regclass);

create function class4_trigfun_record_origination_global_failover() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into origination_global_failover_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into origination_global_failover_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into origination_global_failover_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into origination_global_failover_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_origination_global_failover after update or insert or delete on origination_global_failover for each row execute procedure class4_trigfun_record_origination_global_failover();

--partition_gateway_ref trigger--
drop trigger class4_trig_record_partition_gateway_ref on partition_gateway_ref;
drop table partition_gateway_ref_record;
DROP FUNCTION class4_trigfun_record_partition_gateway_ref();
DROP SEQUENCE partition_gateway_ref_record_record_id_seq;
SELECT * INTO partition_gateway_ref_record FROM partition_gateway_ref where false;
alter table partition_gateway_ref_record add time numeric;
alter table partition_gateway_ref_record add flag character(1);
alter table partition_gateway_ref_record add record_id integer;
alter table partition_gateway_ref_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE partition_gateway_ref_record_record_id_seq;
alter table partition_gateway_ref_record alter COLUMN record_id SET DEFAULT nextval('partition_gateway_ref_record_record_id_seq'::regclass);

create function class4_trigfun_record_partition_gateway_ref() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into partition_gateway_ref_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into partition_gateway_ref_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into partition_gateway_ref_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into partition_gateway_ref_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_partition_gateway_ref after update or insert or delete on partition_gateway_ref for each row execute procedure class4_trigfun_record_partition_gateway_ref();

--payment_term trigger--
drop trigger class4_trig_record_payment_term on payment_term;
drop table payment_term_record;
DROP FUNCTION class4_trigfun_record_payment_term();
DROP SEQUENCE payment_term_record_record_id_seq;
SELECT * INTO payment_term_record FROM payment_term where false;
alter table payment_term_record add time numeric;
alter table payment_term_record add flag character(1);
alter table payment_term_record add record_id integer;
alter table payment_term_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE payment_term_record_record_id_seq;
alter table payment_term_record alter COLUMN record_id SET DEFAULT nextval('payment_term_record_record_id_seq'::regclass);

create function class4_trigfun_record_payment_term() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into payment_term_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into payment_term_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into payment_term_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into payment_term_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_payment_term after update or insert or delete on payment_term for each row execute procedure class4_trigfun_record_payment_term();

--product trigger--
drop trigger class4_trig_record_product on product;
drop table product_record;
DROP FUNCTION class4_trigfun_record_product();
DROP SEQUENCE product_record_record_id_seq;
SELECT * INTO product_record FROM product where false;
alter table product_record add time numeric;
alter table product_record add flag character(1);
alter table product_record add record_id integer;
alter table product_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE product_record_record_id_seq;
alter table product_record alter COLUMN record_id SET DEFAULT nextval('product_record_record_id_seq'::regclass);

create function class4_trigfun_record_product() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into product_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into product_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into product_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into product_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_product after update or insert or delete on product for each row execute procedure class4_trigfun_record_product();

--product_items trigger--
drop trigger class4_trig_record_product_items on product_items;
drop table product_items_record;
DROP FUNCTION class4_trigfun_record_product_items();
DROP SEQUENCE product_items_record_record_id_seq;
SELECT * INTO product_items_record FROM product_items where false;
alter table product_items_record add time numeric;
alter table product_items_record add flag character(1);
alter table product_items_record add record_id integer;
alter table product_items_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE product_items_record_record_id_seq;
alter table product_items_record alter COLUMN record_id SET DEFAULT nextval('product_items_record_record_id_seq'::regclass);

create function class4_trigfun_record_product_items() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into product_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into product_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into product_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into product_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_product_items after update or insert or delete on product_items for each row execute procedure class4_trigfun_record_product_items();

--product_items_resource trigger--
drop trigger class4_trig_record_product_items_resource on product_items_resource;
drop table product_items_resource_record;
DROP FUNCTION class4_trigfun_record_product_items_resource();
DROP SEQUENCE product_items_resource_record_record_id_seq;
SELECT * INTO product_items_resource_record FROM product_items_resource where false;
alter table product_items_resource_record add time numeric;
alter table product_items_resource_record add flag character(1);
alter table product_items_resource_record add record_id integer;
alter table product_items_resource_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE product_items_resource_record_record_id_seq;
alter table product_items_resource_record alter COLUMN record_id SET DEFAULT nextval('product_items_resource_record_record_id_seq'::regclass);

create function class4_trigfun_record_product_items_resource() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into product_items_resource_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into product_items_resource_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into product_items_resource_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into product_items_resource_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_product_items_resource after update or insert or delete on product_items_resource for each row execute procedure class4_trigfun_record_product_items_resource();

--random_ani_generation trigger--
drop trigger class4_trig_record_random_ani_generation on random_ani_generation;
drop table random_ani_generation_record;
DROP FUNCTION class4_trigfun_record_random_ani_generation();
DROP SEQUENCE random_ani_generation_record_record_id_seq;
SELECT * INTO random_ani_generation_record FROM random_ani_generation where false;
alter table random_ani_generation_record add time numeric;
alter table random_ani_generation_record add flag character(1);
alter table random_ani_generation_record add record_id integer;
alter table random_ani_generation_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE random_ani_generation_record_record_id_seq;
alter table random_ani_generation_record alter COLUMN record_id SET DEFAULT nextval('random_ani_generation_record_record_id_seq'::regclass);

create function class4_trigfun_record_random_ani_generation() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into random_ani_generation_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into random_ani_generation_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into random_ani_generation_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into random_ani_generation_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_random_ani_generation after update or insert or delete on random_ani_generation for each row execute procedure class4_trigfun_record_random_ani_generation();

--rate trigger--
drop trigger class4_trig_record_rate on rate;
drop table rate_record;
DROP FUNCTION class4_trigfun_record_rate();
DROP SEQUENCE rate_record_record_id_seq;
SELECT * INTO rate_record FROM rate where false;
alter table rate_record add time numeric;
alter table rate_record add flag character(1);
alter table rate_record add record_id integer;
alter table rate_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE rate_record_record_id_seq;
alter table rate_record alter COLUMN record_id SET DEFAULT nextval('rate_record_record_id_seq'::regclass);

create function class4_trigfun_record_rate() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into rate_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into rate_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into rate_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into rate_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_rate after update or insert or delete on rate for each row execute procedure class4_trigfun_record_rate();

--rate_table trigger--
drop trigger class4_trig_record_rate_table on rate_table;
drop table rate_table_record;
DROP FUNCTION class4_trigfun_record_rate_table();
DROP SEQUENCE rate_table_record_record_id_seq;
SELECT * INTO rate_table_record FROM rate_table where false;
alter table rate_table_record add time numeric;
alter table rate_table_record add flag character(1);
alter table rate_table_record add record_id integer;
alter table rate_table_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE rate_table_record_record_id_seq;
alter table rate_table_record alter COLUMN record_id SET DEFAULT nextval('rate_table_record_record_id_seq'::regclass);

create function class4_trigfun_record_rate_table() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into rate_table_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into rate_table_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into rate_table_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into rate_table_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_rate_table after update or insert or delete on rate_table for each row execute procedure class4_trigfun_record_rate_table();

--resource trigger--
drop trigger class4_trig_record_resource on resource;
drop table resource_record;
DROP FUNCTION class4_trigfun_record_resource();
DROP SEQUENCE resource_record_record_id_seq;
SELECT * INTO resource_record FROM resource where false;
alter table resource_record add time numeric;
alter table resource_record add flag character(1);
alter table resource_record add record_id integer;
alter table resource_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_record_record_id_seq;
alter table resource_record alter COLUMN record_id SET DEFAULT nextval('resource_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource after update or insert or delete on resource for each row execute procedure class4_trigfun_record_resource();

--resource_block trigger--
drop trigger class4_trig_record_resource_block on resource_block;
drop table resource_block_record;
DROP FUNCTION class4_trigfun_record_resource_block();
DROP SEQUENCE resource_block_record_record_id_seq;
SELECT * INTO resource_block_record FROM resource_block where false;
alter table resource_block_record add time numeric;
alter table resource_block_record add flag character(1);
alter table resource_block_record add record_id integer;
alter table resource_block_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_block_record_record_id_seq;
alter table resource_block_record alter COLUMN record_id SET DEFAULT nextval('resource_block_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_block() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_block_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_block_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_block_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_block_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_block after update or insert or delete on resource_block for each row execute procedure class4_trigfun_record_resource_block();

----resource_codecs_ref trigger--
drop trigger class4_trig_record_resource_codecs_ref on resource_codecs_ref;
drop table resource_codecs_ref_record;
DROP FUNCTION class4_trigfun_record_resource_codecs_ref();
DROP SEQUENCE resource_codecs_ref_record_record_id_seq;
SELECT * INTO resource_codecs_ref_record FROM resource_codecs_ref where false;
alter table resource_codecs_ref_record add time numeric;
alter table resource_codecs_ref_record add flag character(1);
alter table resource_codecs_ref_record add record_id integer;
alter table resource_codecs_ref_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_codecs_ref_record_record_id_seq;
alter table resource_codecs_ref_record alter COLUMN record_id SET DEFAULT nextval('resource_codecs_ref_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_codecs_ref() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_codecs_ref_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_codecs_ref_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_codecs_ref_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_codecs_ref_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_codecs_ref after update or insert or delete on resource_codecs_ref for each row execute procedure class4_trigfun_record_resource_codecs_ref();

----resource_direction trigger--
drop trigger class4_trig_record_resource_direction on resource_direction;
drop table resource_direction_record;
DROP FUNCTION class4_trigfun_record_resource_direction();
DROP SEQUENCE resource_direction_record_record_id_seq;
SELECT * INTO resource_direction_record FROM resource_direction where false;
alter table resource_direction_record add time numeric;
alter table resource_direction_record add flag character(1);
alter table resource_direction_record add record_id integer;
alter table resource_direction_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_direction_record_record_id_seq;
alter table resource_direction_record alter COLUMN record_id SET DEFAULT nextval('resource_direction_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_direction() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_direction_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_direction_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_direction_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_direction_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_direction after update or insert or delete on resource_direction for each row execute procedure class4_trigfun_record_resource_direction();

----resource_ip trigger--
drop trigger class4_trig_record_resource_ip on resource_ip;
drop table resource_ip_record;
DROP FUNCTION class4_trigfun_record_resource_ip();
DROP SEQUENCE resource_ip_record_record_id_seq;
SELECT * INTO resource_ip_record FROM resource_ip where false;
alter table resource_ip_record add time numeric;
alter table resource_ip_record add flag character(1);
alter table resource_ip_record add record_id integer;
alter table resource_ip_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_ip_record_record_id_seq;
alter table resource_ip_record alter COLUMN record_id SET DEFAULT nextval('resource_ip_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_ip() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_ip_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_ip_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_ip_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_ip_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_ip after update or insert or delete on resource_ip for each row execute procedure class4_trigfun_record_resource_ip(); 

----resource_ip_limit trigger--
drop trigger class4_trig_record_resource_ip_limit on resource_ip_limit;
drop table resource_ip_limit_record;
DROP FUNCTION class4_trigfun_record_resource_ip_limit();
DROP SEQUENCE resource_ip_limit_record_record_id_seq;
SELECT * INTO resource_ip_limit_record FROM resource_ip_limit where false;
alter table resource_ip_limit_record add time numeric;
alter table resource_ip_limit_record add flag character(1);
alter table resource_ip_limit_record add record_id integer;
alter table resource_ip_limit_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_ip_limit_record_record_id_seq;
alter table resource_ip_limit_record alter COLUMN record_id SET DEFAULT nextval('resource_ip_limit_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_ip_limit() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_ip_limit_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_ip_limit_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_ip_limit_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_ip_limit_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_ip_limit after update or insert or delete on resource_ip_limit for each row execute procedure class4_trigfun_record_resource_ip_limit();

----resource_lrn_action trigger--
drop trigger class4_trig_record_resource_lrn_action on resource_lrn_action;
drop table resource_lrn_action_record;
DROP FUNCTION class4_trigfun_record_resource_lrn_action();
DROP SEQUENCE resource_lrn_action_record_record_id_seq;
SELECT * INTO resource_lrn_action_record FROM resource_lrn_action where false;
alter table resource_lrn_action_record add time numeric;
alter table resource_lrn_action_record add flag character(1);
alter table resource_lrn_action_record add record_id integer;
alter table resource_lrn_action_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_lrn_action_record_record_id_seq;
alter table resource_lrn_action_record alter COLUMN record_id SET DEFAULT nextval('resource_lrn_action_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_lrn_action() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_lrn_action_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_lrn_action_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_lrn_action_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_lrn_action_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_lrn_action after update or insert or delete on resource_lrn_action for each row execute procedure class4_trigfun_record_resource_lrn_action();

----resource_next_route_rule trigger--
drop trigger class4_trig_record_resource_next_route_rule on resource_next_route_rule;
drop table resource_next_route_rule_record;
DROP FUNCTION class4_trigfun_record_resource_next_route_rule();
DROP SEQUENCE resource_next_route_rule_record_record_id_seq;
SELECT * INTO resource_next_route_rule_record FROM resource_next_route_rule where false;
alter table resource_next_route_rule_record add time numeric;
alter table resource_next_route_rule_record add flag character(1);
alter table resource_next_route_rule_record add record_id integer;
alter table resource_next_route_rule_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_next_route_rule_record_record_id_seq;
alter table resource_next_route_rule_record alter COLUMN record_id SET DEFAULT nextval('resource_next_route_rule_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_next_route_rule() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_next_route_rule_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_next_route_rule_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_next_route_rule_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_next_route_rule_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_next_route_rule after update or insert or delete on resource_next_route_rule for each row execute procedure class4_trigfun_record_resource_next_route_rule(); 

----resource_prefix trigger--
drop trigger class4_trig_record_resource_prefix on resource_prefix;
drop table resource_prefix_record;
DROP FUNCTION class4_trigfun_record_resource_prefix();
DROP SEQUENCE resource_prefix_record_record_id_seq;
SELECT * INTO resource_prefix_record FROM resource_prefix where false;
alter table resource_prefix_record add time numeric;
alter table resource_prefix_record add flag character(1);
alter table resource_prefix_record add record_id integer;
alter table resource_prefix_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_prefix_record_record_id_seq;
alter table resource_prefix_record alter COLUMN record_id SET DEFAULT nextval('resource_prefix_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_prefix() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_prefix_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_prefix_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_prefix_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_prefix_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_prefix after update or insert or delete on resource_prefix for each row execute procedure class4_trigfun_record_resource_prefix(); 

----resource_replace_action trigger--
drop trigger class4_trig_record_resource_replace_action on resource_replace_action;
drop table resource_replace_action_record;
DROP FUNCTION class4_trigfun_record_resource_replace_action();
DROP SEQUENCE resource_replace_action_record_record_id_seq;
SELECT * INTO resource_replace_action_record FROM resource_replace_action where false;
alter table resource_replace_action_record add time numeric;
alter table resource_replace_action_record add flag character(1);
alter table resource_replace_action_record add record_id integer;
alter table resource_replace_action_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_replace_action_record_record_id_seq;
alter table resource_replace_action_record alter COLUMN record_id SET DEFAULT nextval('resource_replace_action_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_replace_action() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_replace_action_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_replace_action_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_replace_action_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_replace_action_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_replace_action after update or insert or delete on resource_replace_action for each row execute procedure class4_trigfun_record_resource_replace_action(); 

----resource_translation_ref trigger--
drop trigger class4_trig_record_resource_translation_ref on resource_translation_ref;
drop table resource_translation_ref_record;
DROP FUNCTION class4_trigfun_record_resource_translation_ref();
DROP SEQUENCE resource_translation_ref_record_record_id_seq;
SELECT * INTO resource_translation_ref_record FROM resource_translation_ref where false;
alter table resource_translation_ref_record add time numeric;
alter table resource_translation_ref_record add flag character(1);
alter table resource_translation_ref_record add record_id integer;
alter table resource_translation_ref_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_translation_ref_record_record_id_seq;
alter table resource_translation_ref_record alter COLUMN record_id SET DEFAULT nextval('resource_translation_ref_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_translation_ref() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_translation_ref_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_translation_ref_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_translation_ref_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_translation_ref_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_translation_ref after update or insert or delete on resource_translation_ref for each row execute procedure class4_trigfun_record_resource_translation_ref(); 

----route trigger--
drop trigger class4_trig_record_route on route;
drop table route_record;
DROP FUNCTION class4_trigfun_record_route();
DROP SEQUENCE route_record_record_id_seq;
SELECT * INTO route_record FROM route where false;
alter table route_record add time numeric;
alter table route_record add flag character(1);
alter table route_record add record_id integer;
alter table route_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE route_record_record_id_seq;
alter table route_record alter COLUMN record_id SET DEFAULT nextval('route_record_record_id_seq'::regclass);

create function class4_trigfun_record_route() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into route_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into route_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into route_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into route_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_route after update or insert or delete on route for each row execute procedure class4_trigfun_record_route();

----service_charge_items trigger--
drop trigger class4_trig_record_service_charge_items on service_charge_items;
drop table service_charge_items_record;
DROP FUNCTION class4_trigfun_record_service_charge_items();
DROP SEQUENCE service_charge_items_record_record_id_seq;
SELECT * INTO service_charge_items_record FROM service_charge_items where false;
alter table service_charge_items_record add time numeric;
alter table service_charge_items_record add flag character(1);
alter table service_charge_items_record add record_id integer;
alter table service_charge_items_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE service_charge_items_record_record_id_seq;
alter table service_charge_items_record alter COLUMN record_id SET DEFAULT nextval('service_charge_items_record_record_id_seq'::regclass);

create function class4_trigfun_record_service_charge_items() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into service_charge_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into service_charge_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into service_charge_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into service_charge_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_service_charge_items after update or insert or delete on service_charge_items for each row execute procedure class4_trigfun_record_service_charge_items();

----sip_error_code trigger--
drop trigger class4_trig_record_sip_error_code on sip_error_code;
drop table sip_error_code_record;
DROP FUNCTION class4_trigfun_record_sip_error_code();
DROP SEQUENCE sip_error_code_record_record_id_seq;
SELECT * INTO sip_error_code_record FROM sip_error_code where false;
alter table sip_error_code_record add time numeric;
alter table sip_error_code_record add flag character(1);
alter table sip_error_code_record add record_id integer;
alter table sip_error_code_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE sip_error_code_record_record_id_seq;
alter table sip_error_code_record alter COLUMN record_id SET DEFAULT nextval('sip_error_code_record_record_id_seq'::regclass);

create function class4_trigfun_record_sip_error_code() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into sip_error_code_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into sip_error_code_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into sip_error_code_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into sip_error_code_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_sip_error_code after update or insert or delete on sip_error_code for each row execute procedure class4_trigfun_record_sip_error_code();

----switch_profile trigger--
drop trigger class4_trig_record_switch_profile on switch_profile;
drop table switch_profile_record;
DROP FUNCTION class4_trigfun_record_switch_profile();
DROP SEQUENCE switch_profile_record_record_id_seq;
SELECT * INTO switch_profile_record FROM switch_profile where false;
alter table switch_profile_record add time numeric;
alter table switch_profile_record add flag character(1);
alter table switch_profile_record add record_id integer;
alter table switch_profile_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE switch_profile_record_record_id_seq;
alter table switch_profile_record alter COLUMN record_id SET DEFAULT nextval('switch_profile_record_record_id_seq'::regclass);

create function class4_trigfun_record_switch_profile() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into switch_profile_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into switch_profile_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into switch_profile_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into switch_profile_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_switch_profile after update or insert or delete on switch_profile for each row execute procedure class4_trigfun_record_switch_profile();

----termination_global_failover trigger--
drop trigger class4_trig_record_termination_global_failover on termination_global_failover;
drop table termination_global_failover_record;
DROP FUNCTION class4_trigfun_record_termination_global_failover();
DROP SEQUENCE termination_global_failover_record_record_id_seq;
SELECT * INTO termination_global_failover_record FROM termination_global_failover where false;
alter table termination_global_failover_record add time numeric;
alter table termination_global_failover_record add flag character(1);
alter table termination_global_failover_record add record_id integer;
alter table termination_global_failover_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE termination_global_failover_record_record_id_seq;
alter table termination_global_failover_record alter COLUMN record_id SET DEFAULT nextval('termination_global_failover_record_record_id_seq'::regclass);

create function class4_trigfun_record_termination_global_failover() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into termination_global_failover_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into termination_global_failover_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into termination_global_failover_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into termination_global_failover_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_termination_global_failover after update or insert or delete on termination_global_failover for each row execute procedure class4_trigfun_record_termination_global_failover();

----time_profile trigger--
drop trigger class4_trig_record_time_profile on time_profile;
drop table time_profile_record;
DROP FUNCTION class4_trigfun_record_time_profile();
DROP SEQUENCE time_profile_record_record_id_seq;
SELECT * INTO time_profile_record FROM time_profile where false;
alter table time_profile_record add time numeric;
alter table time_profile_record add flag character(1);
alter table time_profile_record add record_id integer;
alter table time_profile_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE time_profile_record_record_id_seq;
alter table time_profile_record alter COLUMN record_id SET DEFAULT nextval('time_profile_record_record_id_seq'::regclass);

create function class4_trigfun_record_time_profile() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into time_profile_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into time_profile_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into time_profile_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into time_profile_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_time_profile after update or insert or delete on time_profile for each row execute procedure class4_trigfun_record_time_profile();

----transaction_fee_items trigger--
drop trigger class4_trig_record_transaction_fee_items on transaction_fee_items;
drop table transaction_fee_items_record;
DROP FUNCTION class4_trigfun_record_transaction_fee_items();
DROP SEQUENCE transaction_fee_items_record_record_id_seq;
SELECT * INTO transaction_fee_items_record FROM transaction_fee_items where false;
alter table transaction_fee_items_record add time numeric;
alter table transaction_fee_items_record add flag character(1);
alter table transaction_fee_items_record add record_id integer;
alter table transaction_fee_items_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE transaction_fee_items_record_record_id_seq;
alter table transaction_fee_items_record alter COLUMN record_id SET DEFAULT nextval('transaction_fee_items_record_record_id_seq'::regclass);

create function class4_trigfun_record_transaction_fee_items() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into transaction_fee_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into transaction_fee_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into transaction_fee_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into transaction_fee_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_transaction_fee_items after update or insert or delete on transaction_fee_items for each row execute procedure class4_trigfun_record_transaction_fee_items();

----translation_item trigger--
drop trigger class4_trig_record_translation_item on translation_item;
drop table translation_item_record;
DROP FUNCTION class4_trigfun_record_translation_item();
DROP SEQUENCE translation_item_record_record_id_seq;
SELECT * INTO translation_item_record FROM translation_item where false;
alter table translation_item_record add time numeric;
alter table translation_item_record add flag character(1);
alter table translation_item_record add record_id integer;
alter table translation_item_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE translation_item_record_record_id_seq;
alter table translation_item_record alter COLUMN record_id SET DEFAULT nextval('translation_item_record_record_id_seq'::regclass);

create function class4_trigfun_record_translation_item() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into translation_item_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into translation_item_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into translation_item_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into translation_item_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_translation_item after update or insert or delete on translation_item for each row execute procedure class4_trigfun_record_translation_item();

--resource_block_items trigger--
drop trigger class4_trig_record_resource_block_items on resource_block_items;
drop table resource_block_items_record;
DROP FUNCTION class4_trigfun_record_resource_block_items();
DROP SEQUENCE resource_block_items_record_record_id_seq;
SELECT * INTO resource_block_items_record FROM resource_block_items where false;
alter table resource_block_items_record add time numeric;
alter table resource_block_items_record add flag character(1);
alter table resource_block_items_record add record_id integer;
alter table resource_block_items_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_block_items_record_record_id_seq;
alter table resource_block_items_record alter COLUMN record_id SET DEFAULT nextval('resource_block_items_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_block_items() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_block_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_block_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_block_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_block_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_block_items after update or insert or delete on resource_block_items for each row execute procedure class4_trigfun_record_resource_block_items();

----trunk_pstn_header trigger--
drop trigger class4_trig_record_trunk_pstn_header on trunk_pstn_header;
drop table trunk_pstn_header_record;
DROP FUNCTION class4_trigfun_record_trunk_pstn_header();
DROP SEQUENCE trunk_pstn_header_record_record_id_seq;
SELECT * INTO trunk_pstn_header_record FROM trunk_pstn_header where false;
alter table trunk_pstn_header_record add time numeric;
alter table trunk_pstn_header_record add flag character(1);
alter table trunk_pstn_header_record add record_id integer;
alter table trunk_pstn_header_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE trunk_pstn_header_record_record_id_seq;
alter table trunk_pstn_header_record alter COLUMN record_id SET DEFAULT nextval('trunk_pstn_header_record_record_id_seq'::regclass);

create function class4_trigfun_record_trunk_pstn_header() returns trigger as $$
begin

        if(TG_OP='INSERT')then
                insert into trunk_pstn_header_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into trunk_pstn_header_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into trunk_pstn_header_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into trunk_pstn_header_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_trunk_pstn_header after update or insert or delete on trunk_pstn_header for each row execute procedure class4_trigfun_record_trunk_pstn_header();

----c4_lrn trigger--
drop trigger class4_trig_record_c4_lrn on c4_lrn;
drop table c4_lrn_record;
DROP FUNCTION class4_trigfun_record_c4_lrn();
DROP SEQUENCE c4_lrn_record_record_id_seq;
SELECT * INTO c4_lrn_record FROM c4_lrn where false;
alter table c4_lrn_record add time numeric;
alter table c4_lrn_record add flag character(1);
alter table c4_lrn_record add record_id integer;
alter table c4_lrn_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE c4_lrn_record_record_id_seq;
alter table c4_lrn_record alter COLUMN record_id SET DEFAULT nextval('c4_lrn_record_record_id_seq'::regclass);

create function class4_trigfun_record_c4_lrn() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into c4_lrn_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into c4_lrn_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into c4_lrn_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into c4_lrn_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_c4_lrn after update or insert or delete on c4_lrn for each row execute procedure class4_trigfun_record_c4_lrn();

----currency trigger--
drop trigger class4_trig_record_currency on currency;
drop table currency_record;
DROP FUNCTION class4_trigfun_record_currency();
DROP SEQUENCE currency_record_record_id_seq;
SELECT * INTO currency_record FROM currency where false;
alter table currency_record add time numeric;
alter table currency_record add flag character(1);
alter table currency_record add record_id integer;
alter table currency_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE currency_record_record_id_seq;
alter table currency_record alter COLUMN record_id SET DEFAULT nextval('currency_record_record_id_seq'::regclass);

create function class4_trigfun_record_currency() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into currency_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into currency_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into currency_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into currency_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_currency after update or insert or delete on currency for each row execute procedure class4_trigfun_record_currency();

----lrn_groups trigger--
drop trigger class4_trig_record_lrn_groups on lrn_groups;
drop table lrn_groups_record;
DROP FUNCTION class4_trigfun_record_lrn_groups();
DROP SEQUENCE lrn_groups_record_record_id_seq;
SELECT * INTO lrn_groups_record FROM lrn_groups where false;
alter table lrn_groups_record add time numeric;
alter table lrn_groups_record add flag character(1);
alter table lrn_groups_record add record_id integer;
alter table lrn_groups_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE lrn_groups_record_record_id_seq;
alter table lrn_groups_record alter COLUMN record_id SET DEFAULT nextval('lrn_groups_record_record_id_seq'::regclass);

create function class4_trigfun_record_lrn_groups() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into lrn_groups_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into lrn_groups_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into lrn_groups_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into lrn_groups_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_lrn_groups after update or insert or delete on lrn_groups for each row execute procedure class4_trigfun_record_lrn_groups();

----lrn_items trigger--
drop trigger class4_trig_record_lrn_items on lrn_items;
drop table lrn_items_record;
DROP FUNCTION class4_trigfun_record_lrn_items();
DROP SEQUENCE lrn_items_record_record_id_seq;
SELECT * INTO lrn_items_record FROM lrn_items where false;
alter table lrn_items_record add time numeric;
alter table lrn_items_record add flag character(1);
alter table lrn_items_record add record_id integer;
alter table lrn_items_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE lrn_items_record_record_id_seq;
alter table lrn_items_record alter COLUMN record_id SET DEFAULT nextval('lrn_items_record_record_id_seq'::regclass);

create function class4_trigfun_record_lrn_items() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into lrn_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into lrn_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into lrn_items_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into lrn_items_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_lrn_items after update or insert or delete on lrn_items for each row execute procedure class4_trigfun_record_lrn_items();

----agent_client_client trigger--
drop trigger class4_trig_record_agent_client_client on agent_client_client;
drop table agent_client_client_record;
DROP FUNCTION class4_trigfun_record_agent_client_client();
DROP SEQUENCE agent_client_client_record_record_id_seq;
SELECT * INTO agent_client_client_record FROM agent_client_client where false;
alter table agent_client_client_record add time numeric;
alter table agent_client_client_record add flag character(1);
alter table agent_client_client_record add record_id integer;
alter table agent_client_client_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE agent_client_client_record_record_id_seq;
alter table agent_client_client_record alter COLUMN record_id SET DEFAULT nextval('agent_client_client_record_record_id_seq'::regclass);

create function class4_trigfun_record_agent_client_client() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into agent_client_client_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into agent_client_client_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into agent_client_client_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into agent_client_client_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_agent_client_client after update or insert or delete on agent_client_client for each row execute procedure class4_trigfun_record_agent_client_client();

----buy_order trigger--
drop trigger class4_trig_record_buy_order on buy_order;
drop table buy_order_record;
DROP FUNCTION class4_trigfun_record_buy_order();
DROP SEQUENCE buy_order_record_record_id_seq;
SELECT * INTO buy_order_record FROM buy_order where false;
alter table buy_order_record add time numeric;
alter table buy_order_record add flag character(1);
alter table buy_order_record add record_id integer;
alter table buy_order_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE buy_order_record_record_id_seq;
alter table buy_order_record alter COLUMN record_id SET DEFAULT nextval('buy_order_record_record_id_seq'::regclass);

create function class4_trigfun_record_buy_order() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into buy_order_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into buy_order_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into buy_order_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into buy_order_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_buy_order after update or insert or delete on buy_order for each row execute procedure class4_trigfun_record_buy_order();

----client_commit_code trigger--
drop trigger class4_trig_record_client_commit_code on client_commit_code;
drop table client_commit_code_record;
DROP FUNCTION class4_trigfun_record_client_commit_code();
DROP SEQUENCE client_commit_code_record_record_id_seq;
SELECT * INTO client_commit_code_record FROM client_commit_code where false;
alter table client_commit_code_record add time numeric;
alter table client_commit_code_record add flag character(1);
alter table client_commit_code_record add record_id integer;
alter table client_commit_code_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE client_commit_code_record_record_id_seq;
alter table client_commit_code_record alter COLUMN record_id SET DEFAULT nextval('client_commit_code_record_record_id_seq'::regclass);

create function class4_trigfun_record_client_commit_code() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into client_commit_code_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into client_commit_code_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into client_commit_code_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into client_commit_code_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_client_commit_code after update or insert or delete on client_commit_code for each row execute procedure class4_trigfun_record_client_commit_code();

----client_commit_minutes trigger--
drop trigger class4_trig_record_client_commit_minutes on client_commit_minutes;
drop table client_commit_minutes_record;
DROP FUNCTION class4_trigfun_record_client_commit_minutes();
DROP SEQUENCE client_commit_minutes_record_record_id_seq;
SELECT * INTO client_commit_minutes_record FROM client_commit_minutes where false;
alter table client_commit_minutes_record add time numeric;
alter table client_commit_minutes_record add flag character(1);
alter table client_commit_minutes_record add record_id integer;
alter table client_commit_minutes_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE client_commit_minutes_record_record_id_seq;
alter table client_commit_minutes_record alter COLUMN record_id SET DEFAULT nextval('client_commit_minutes_record_record_id_seq'::regclass);

create function class4_trigfun_record_client_commit_minutes() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into client_commit_minutes_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into client_commit_minutes_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into client_commit_minutes_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into client_commit_minutes_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_client_commit_minutes after update or insert or delete on client_commit_minutes for each row execute procedure class4_trigfun_record_client_commit_minutes();

----contract trigger--
drop trigger class4_trig_record_contract on contract;
drop table contract_record;
DROP FUNCTION class4_trigfun_record_contract();
DROP SEQUENCE contract_record_record_id_seq;
SELECT * INTO contract_record FROM contract where false;
alter table contract_record add time numeric;
alter table contract_record add flag character(1);
alter table contract_record add record_id integer;
alter table contract_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE contract_record_record_id_seq;
alter table contract_record alter COLUMN record_id SET DEFAULT nextval('contract_record_record_id_seq'::regclass);

create function class4_trigfun_record_contract() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into contract_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into contract_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into contract_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into contract_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_contract after update or insert or delete on contract for each row execute procedure class4_trigfun_record_contract();

----resource_rate trigger--
drop trigger class4_trig_record_resource_rate on resource_rate;
drop table resource_rate_record;
DROP FUNCTION class4_trigfun_record_resource_rate();
DROP SEQUENCE resource_rate_record_record_id_seq;
SELECT * INTO resource_rate_record FROM resource_rate where false;
alter table resource_rate_record add time numeric;
alter table resource_rate_record add flag character(1);
alter table resource_rate_record add record_id integer;
alter table resource_rate_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_rate_record_record_id_seq;
alter table resource_rate_record alter COLUMN record_id SET DEFAULT nextval('resource_rate_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_rate() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_rate_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_rate_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_rate_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_rate_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_rate after update or insert or delete on resource_rate for each row execute procedure class4_trigfun_record_resource_rate();

----sell_order trigger--
drop trigger class4_trig_record_sell_order on sell_order;
drop table sell_order_record;
DROP FUNCTION class4_trigfun_record_sell_order();
DROP SEQUENCE sell_order_record_record_id_seq;
SELECT * INTO sell_order_record FROM sell_order where false;
alter table sell_order_record add time numeric;
alter table sell_order_record add flag character(1);
alter table sell_order_record add record_id integer;
alter table sell_order_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE sell_order_record_record_id_seq;
alter table sell_order_record alter COLUMN record_id SET DEFAULT nextval('sell_order_record_record_id_seq'::regclass);

create function class4_trigfun_record_sell_order() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into sell_order_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into sell_order_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into sell_order_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into sell_order_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_sell_order after update or insert or delete on sell_order for each row execute procedure class4_trigfun_record_sell_order();



----product_rout_rate_table trigger--
drop trigger class4_trig_record_product_rout_rate_table on product_rout_rate_table;
drop table product_rout_rate_table_record;
DROP FUNCTION class4_trigfun_record_product_rout_rate_table();
DROP SEQUENCE product_rout_rate_table_record_record_id_seq;
SELECT * INTO product_rout_rate_table_record FROM product_rout_rate_table where false;
alter table product_rout_rate_table_record add time numeric;
alter table product_rout_rate_table_record add flag character(1);
alter table product_rout_rate_table_record add record_id integer;
alter table product_rout_rate_table_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE product_rout_rate_table_record_record_id_seq;
alter table product_rout_rate_table_record alter COLUMN record_id SET DEFAULT nextval('product_rout_rate_table_record_record_id_seq'::regclass);

create function class4_trigfun_record_product_rout_rate_table() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into product_rout_rate_table_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into product_rout_rate_table_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into product_rout_rate_table_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into product_rout_rate_table_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

----resource_capacity and trigger --
drop trigger class4_trig_record_resource_capacity on resource_capacity;
drop table resource_capacity_record;
DROP FUNCTION class4_trigfun_record_resource_capacity();
DROP SEQUENCE resource_capacity_record_record_id_seq;
SELECT * INTO resource_capacity_record FROM resource_capacity where false;
alter table resource_capacity_record add time numeric;
alter table resource_capacity_record add flag character(1);
alter table resource_capacity_record add record_id integer;
alter table resource_capacity_record alter COLUMN record_id SET NOT NULL;
create SEQUENCE resource_capacity_record_record_id_seq;
alter table resource_capacity_record alter COLUMN record_id SET DEFAULT nextval('resource_capacity_record_record_id_seq'::regclass);

create function class4_trigfun_record_resource_capacity() returns trigger as $$
begin
        if(TG_OP='INSERT')then
                insert into resource_capacity_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'I';
        elseif(TG_OP='DELETE')then
                insert into resource_capacity_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'D';
        elseif(TG_OP='UPDATE')then
                insert into resource_capacity_record select OLD.*,EXTRACT(EPOCH from current_timestamp(0)),'B';
                insert into resource_capacity_record select NEW.*,EXTRACT(EPOCH from current_timestamp(0)),'A';
        end if;
return null;
end;
$$ language plpgsql;

create trigger class4_trig_record_resource_capacity after update or insert or delete on resource_capacity for each row execute procedure class4_trigfun_record_resource_capacity();
create index c4_resource_capacity_record_id_idx on resource_capacity_record (record_id);

create trigger class4_trig_record_product_rout_rate_table after update or insert or delete on product_rout_rate_table for each row execute procedure class4_trigfun_record_product_rout_rate_table();

create index c4_resource_ip_record_id_idx on resource_ip_record (record_id);

create index c4_resource_record_id_idx on resource_record (record_id);

create index c4_resource_direction_record_id_idx on resource_direction_record (record_id);

create index c4_resource_block_record_id_idx on resource_block_record (record_id);

create index c4_resource_codecs_ref_record_id_idx on resource_codecs_ref_record (record_id);

create index c4_product_items_record_id_idx on product_items_record (record_id);

create index c4_translation_item_record_id_idx on translation_item_record (record_id);

create index c4_product_record_id_idx on product_record (record_id);

create index c4_dynamic_route_record_id_idx on dynamic_route_record (record_id);

create index c4_route_record_id_idx on route_record (record_id);

create index c4_time_profile_record_id_idx on time_profile_record (record_id);

create index c4_resource_ip_limit_record_id_idx on resource_ip_limit_record (record_id);

create index c4_resource_translation_ref_record_id_idx on resource_translation_ref_record (record_id);

create index c4_jurisdiction_prefix_record_id_idx on jurisdiction_prefix_record (record_id);

create index c4_rate_table_record_id_idx on rate_table_record (record_id);

create index c4_resource_next_route_rule_record_id_idx on resource_next_route_rule_record (record_id);

create index c4_sip_error_code_record_id_idx on sip_error_code_record (record_id);

create index c4_resource_prefix_record_id_idx on resource_prefix_record  (record_id);

create index c4_resource_lrn_action_record_id_idx on resource_lrn_action_record (record_id);

create index c4_product_items_resource_record_id_idx on product_items_resource_record (record_id);

create index c4_dynamic_route_items_record_id_idx on dynamic_route_items_record  (record_id);

create index c4_client_record_id_idx on client_record (record_id);

create index c4_dynamic_route_pri_record_id_idx on dynamic_route_pri_record (record_id);

create index c4_dynamic_route_qos_record_id_idx on dynamic_route_qos_record (record_id);

create index c4_dynamic_route_override_record_id_idx on dynamic_route_override_record (record_id);

create index c4_service_charge_items_record_id_idx on service_charge_items_record (record_id);

create index c4_payment_term_record_id_idx on payment_term_record  (record_id);

create index c4_currency_updates_record_id_idx on currency_updates_record (record_id);

create index c4_switch_profile_record_id_idx on switch_profile_record (record_id);

create index c4_transaction_fee_items_record_id_idx on transaction_fee_items_record (record_id);

create index c4_egress_profile_record_id_idx on egress_profile_record (record_id);

create index c4_code_record_id_idx on code_record (record_id);

create index c4_resource_replace_action_record_id_idx on resource_replace_action_record (record_id);

create index c4_agent_client_client_record_id_idx on agent_client_client_record (record_id);

create index c4_exchange_par_account_record_id_idx on exchange_par_account_record (record_id);

create index c4_partition_gateway_ref_record_id_idx on partition_gateway_ref_record (record_id);

create index c4_origination_global_failover_record_id_idx on origination_global_failover_record (record_id);

create index c4_termination_global_failover_record_id_idx on termination_global_failover_record (record_id);

create index c4_global_route_error_record_id_idx on global_route_error_record (record_id);

create index c4_resource_block_items_record_id_idx on resource_block_items_record (record_id);

create index c4_random_ani_generation_record_id_idx on random_ani_generation_record (record_id);

create index c4_allowed_sendto_ip_record_id_idx on allowed_sendto_ip_record (record_id);

create index c4_c4_lrn_record_id_idx on c4_lrn_record (record_id);

create index c4_product_rout_rate_table_record_id_idx on product_rout_rate_table_record (record_id);

create index c4_rate_record_id_idx on rate_record (record_id);
