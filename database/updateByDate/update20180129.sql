ALTER TABLE did_billing_plan ADD COLUMN pay_type SMALLINT DEFAULT 0;
COMMENT ON COLUMN did_billing_plan.pay_type IS '0 - Weekly; 1 - Monthly';
alter table did_billing_rel drop column fee_per_port;
alter table did_billing_plan add column fee_per_port numeric default 0;
