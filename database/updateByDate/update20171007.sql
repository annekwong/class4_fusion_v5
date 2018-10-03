insert into sys_pri ( pri_name, module_id, pri_val, flag, pri_url)
values
( 'did_report',(select id from sys_module where module_name='Origination'),'DID Report', true, 'cdrapi/did_report'),
( 'orig_invoice',(select id from sys_module where module_name='Origination'),'Invoice', true, '	did/orig_invoice/view/');
insert into sys_pri ( pri_name, module_id, pri_val, flag, pri_url)
values
( 'reports_db:commission',(select id from sys_module where module_name='Agent'),'Commission Report', true, 'reports_db/commission');
update sys_pri set flag='false' where pri_name='rerate';
update version_information set major_ver='V5.2.20171007' where id IN(
       SELECT max(id) FROM version_information
)