alter table did_billing_plan add column price_type smallint default 0;
COMMENT ON COLUMN did_billing_plan.price_type IS '0 - Weekly; 1 - Monthly';