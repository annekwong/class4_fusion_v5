

1.psql -U postgres db < class4_db_schema.sql -----  Structure of schma

2.psql -U postgres db < db_data.sql  -----  Basic data

3.psql -U postgres db < code.sql ----Code data

4.psql -U postgres db < jurisdiction_prefix.sql  ----- jurisdiction data

5.psql -U postgres db < voip.sql  ------ voip gateway 

6.psql -U postgres db < update.sql  ----- Update record data


Note: Change switch_profile  Server  ip =  update switch_profile set sip_ip ='192.99.10.113' ;

