--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.4
-- Dumped by pg_dump version 9.6.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET search_path = public, pg_catalog;

--
-- Data for Name: c4_livecall_user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY c4_livecall_user (id, name, password, user_type) FROM stdin;
1	demo	123456	0
2	class4	123456	0
\.


--
-- Name: c4_livecall_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('c4_livecall_user_id_seq', 1, true);


--
-- Data for Name: c4_lrn; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY c4_lrn (id, srv1_ip, srv1_port, srv2_ip, srv2_port) FROM stdin;
1	108.165.2.58	5060	74.117.36.137	5060
\.


--
-- Name: c4_lrn_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('c4_lrn_id_seq', 1, false);


--
-- Data for Name: cleanup; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY cleanup (id, name, backup_frequency, data_size, data_cleansing_frequency, data_removal, ftp_server, ftp_user, ftp_password, actived, last_time) FROM stdin;
1	CDR Export Log	\N	\N	\N	\N	\N	\N	\N	t	\N
2	Modification Log	\N	\N	\N	\N	\N	\N	\N	t	\N
3	Invoice	\N	\N	\N	\N	\N	\N	\N	t	\N
4	Import Log	\N	\N	\N	\N	\N	\N	\N	t	\N
5	Expired Rates	\N	\N	\N	\N	\N	\N	\N	t	\N
\.


--
-- Name: cleanup_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cleanup_id_seq', 1, false);


--
-- Data for Name: codecs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY codecs (id, name, detail) FROM stdin;
3	GSM(8000)	European GSM Full Rate Audio 13 kbit/s (GSM 06.10)
4	G723(8000)	ITU-T G.723.1
9	G722(8000)	ITU-T G.722 Audio
13	CN(8000)	Comfort noise
15	G728(8000)	ITU-T G.728 Audio 16 kbit/s
16	DV14(11025)	IMA ADPCM
17	DV14(22050)	IMA ADPCM
18	G729(8000)	ITU-T G.729 and G.729a
96	AMR(8000)/dynamic	Adaptive Multi-Rate audio
97	iLBC/dynamic	Internet low Bitrate Codec 13.33 or 15.2 kbit/s
99	Speex(8000, 16000 or 32000)/dynamic	RTP Payload Format for the Speex Codec
101	telephone-event/dynamic	?
107	G7221(16000 or 32000)/dynamic	ITU-T G.722.1
115	G7221C(32000)/dynamic	?
121	G726-40(8000)/dynamic	ITU-T G.726 audio with 40 kbit/s
122	G726-32(8000)/dynamic	ITU-T G.726 audio with 32 kbit/s
123	G726-24(8000)/dynamic	ITU-T G.726 audio with 24 kbit/s
124	G726-16(8000)/dynamic	ITU-T G.726 audio with 16 kbit/s
0	PCMU(G711.a 8000)	ITU-T G.711 PCM µ-Law Audio 64 kbit/s
8	PCMA(G711.u 8000)	ITU-T G.711 PCM A-Law Audio 64 kbit/s
145	G729a	\N
146	G729b	\N
\.


--
-- Data for Name: currency; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY currency (currency_id, code, active, update_by) FROM stdin;
1	USA	t	admin
\.


--
-- Data for Name: currency_updates; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY currency_updates (currency_id, modify_time, rate, last_rate, currency_updates_id) FROM stdin;
1	2015-11-23 08:05:05+00	1.000000	\N	1
\.


--
-- Name: currency_updates_currency_updates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('currency_updates_currency_updates_id_seq', 1, false);


--
-- Data for Name: global_failover; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY global_failover (id, failover_strategy, from_sip_code, to_sip_code, to_sip_string) FROM stdin;
169	3	300	300	Multiple Choices
171	2	302	302	Moved Temporarily
172	2	305	305	Use Proxy
174	2	400	400	Bad Request
175	2	401	401	Unauthorized
176	2	402	402	Payment Required
177	2	403	403	Forbidden
178	2	404	404	Not Found
179	2	405	405	Method Not Allowed
180	2	406	406	Not Acceptable
181	2	407	407	Proxy Authentication Required
182	2	408	408	Request Timeout
183	2	409	409	Conflict
184	2	410	410	Gone
185	2	411	411	Length Required
186	2	412	412	Precondition Failed
187	2	413	413	Request Entity Too Large
188	2	414	414	Request-URI Too Long
189	2	415	415	Unsupported Media Type
190	2	416	416	Unsupported URI Scheme
191	2	417	417	Unknown Resource-Priority
192	2	420	420	Bad Extension
193	2	421	421	Extension Required
194	2	422	422	Session Interval Too Small
195	2	423	423	Interval Too Brief
196	1	480	480	Temporarily Unavailable
197	1	481	481	Transaction Does Not Exist
198	1	482	482	Loop Detected
199	1	483	483	Too Many Hops
200	1	484	484	Address Incomplete
201	1	485	485	Ambiguous
202	3	486	486	Busy Here
203	3	487	487	Request Terminated
204	1	488	488	Not Acceptable Here
205	1	489	489	Bad Event
206	2	490	490	Request Updated
207	2	491	491	Request Pending
208	2	493	493	Undecipherable
209	2	494	494	Security Agreement Required
210	1	500	500	Internal Server Error
211	1	501	501	Not Implemented
212	1	502	502	Bad Gateway
213	2	503	503	Service Unavailable
214	1	504	504	Gateway Time-out
215	1	505	505	Version Not Supported
216	1	513	513	Message Too Large
217	1	580	580	Precondition Failure
218	2	600	600	Busy Everywhere
219	2	603	603	Decline
220	2	604	604	Does Not Exist Anywhere
221	2	606	606	Not Acceptable
222	1	687	687	Dialog Terminated
173	2	380	380	Alternative Service
\.


--
-- Name: global_failover_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('global_failover_id_seq', 284, true);


--
-- Data for Name: global_route_error; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY global_route_error (id, error_code, error_description, to_sip_code, to_sip_string, default_to_sip_code, default_to_sip_string) FROM stdin;
3	0	Invalid Argument	\N	\N	403	Forbidden
4	1	System Limit CAP Exceeded	\N	\N	503	Service Unavailable
5	2	System Limit CPS Exceeded	\N	\N	503	Service Unavailable
6	3	Unauthorized IP Address	\N	\N	403	Forbidden
7	4	No Ingress Resource Found	\N	\N	403	Forbidden
8	5	No Product Found	\N	\N	403	Forbidden
19	21	Balance Use Up	\N	\N	402	Payment Required
20	22	No Routing Plan Route	\N	\N	403	Forbidden
45	47	LRN Loop Detected	\N	\N	482	Loop Detected
46	48	Reject Partition	\N	\N	403	Forbidden
9	6	Trunk Limit CAP Exceeded	\N	\N	503	Service Unavailable
21	23	No Routing Plan Prefix	\N	\N	403	Forbidden
22	24	Ingress Rate No Configure	\N	\N	403	Forbidden
23	25	Termination Invalid Codec Negotiation	\N	\N	415	Unsupported Media Type
10	7	Trunk Limit CPS Exceeded	\N	\N	503	Service Unavailable
11	8	IP Limit CAP Exceeded	\N	\N	503	Service Unavailable
12	9	IP Limit CPS Exceeded	\N	\N	503	Service Unavailable
13	10	Invalid Codec Negotiation	\N	\N	415	Unsupported Media Type
14	11	Block Due To LRN	\N	\N	403	Forbidden
15	12	Ingress Rate Not Found	\N	\N	403	Forbidden
25	27	All egress No Confirmed	\N	\N	503	Service Unavailable
26	28	LRN Response No Exist DNIS	\N	\N	403	Forbidden
16	13	Egress Trunk Not Found	\N	\N	403	Forbidden
17	18	All Egress Not Available	\N	\N	503	Service Unavailable
18	20	Ingress Resource Disabled	\N	\N	403	Forbidden
24	26	No Codec Found	\N	\N	415	Unsupported Media Type
27	29	Carrier CAP Limit Exceeded	\N	\N	503	Service Unavailable
28	30	Carrier CPS Limit Exceeded	\N	\N	503	Service Unavailable
29	31	Host Alert Reject	\N	\N	403	Forbidden
30	32	Resource Alert Reject	\N	\N	403	Forbidden
31	33	Resource Reject H323	\N	\N	403	Forbidden
32	34	180 Negotiation SDP Failed	\N	\N	415	Unsupported Media Type
36	38	Trunk Block ANI	\N	\N	403	Forbidden
33	35	183 Negotiation SDP Failed	\N	\N	415	Unsupported Media Type
34	36	200 Negotiation SDP Failed	\N	\N	415	Unsupported Media Type
35	37	LRN Block Higher Rate	\N	\N	403	Forbidden
37	39	Trunk Block DNIS	\N	\N	403	Forbidden
38	40	Trunk Block ALL	\N	\N	403	Forbidden
39	41	Block ANI	\N	\N	403	Forbidden
40	42	Block DNIS	\N	\N	403	Forbidden
41	43	Block ALL	\N	\N	403	Forbidden
42	44	T38 Reject	\N	\N	503	Service Unavailable
43	45	Partition CAP Limit Exceeded	\N	\N	503	Service Unavailable
44	46	Partition CPS Limit Exceeded	\N	\N	503	Service Unavailable
\.


--
-- Name: global_route_error_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('global_route_error_id_seq', 90, true);


--
-- Data for Name: lrn; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY lrn (lrn_id, ip1, port1, ip2, port2, timeout1, timeout2) FROM stdin;
\.


--
-- Name: lrn_lrn_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('lrn_lrn_id_seq', 1, false);


--
-- Data for Name: mail_tmplate; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY mail_tmplate (id, invoice_from, invoice_to, invoice_subject, invoice_content, payment_from, payment_to, payment_subject, payment_content, lowbalance_subject, lowbalance_content, noc_email_subject, noc_email_content, carrier_email_subject, carrier_email_content, alert_email_subject, alert_email_content, carrier_invoice_subject, carrier_invoice_content, auto_summary_subject, auto_summary_content, auto_balance_subject, auto_balance_content, auto_delivery_subject, auto_delivery_content, auto_cdr_subject, auto_cdr_content, no_route_available_alert_email_subject, no_route_available_alert_email_content, target_match_alert_email_subject, target_match_alert_email_content, rate_watch_alert_email_subject, rate_watch_alert_email_content, route_update_alert_email_subject, route_update_alert_email_content, rate_update_alert_email_subject, rate_update_alert_email_content, low_balance_alert_email_subject, low_balance_alert_email_content, new_invoice_posted_mail_alert_email_subject, new_invoice_posted_mail_alert_email_content, payment_sent_subject, payment_sent_content, payment_received_subject, payment_received_content, trouble_ticket_subject, trouble_ticket_content, send_cdr_subject, send_cdr_content, select_route_up_email_subject, select_route_up_email_content, exchange_auto_summary_subject, exchange_auto_summary_content, finance_alert_subject, finance_alert_content, buy_qos_alert_subject, buy_qos_alert_content, sell_qos_alert_subject, sell_qos_alert_content, lowbalance_from, noc_email_from, carrier_email_from, alert_email_from, auto_summary_from, auto_balance_from, auto_cdr_from, payment_sent_from, payment_received_from, trouble_ticket_from, send_cdr_from, no_route_available_alert_email_from, target_match_alert_email_from, rate_watch_alert_email_from, route_update_alert_email_from, rate_update_alert_email_from, low_balance_alert_email_from, select_route_up_email_from, new_invoice_posted_mail_alert_email_from, exchange_auto_summary_from, finance_alert_from, buy_qos_alert_from, sell_qos_alert_from, rate_mail_success_subject, rate_mail_success_content, rate_mail_success_from, rate_mail_fail_subject, rate_mail_fail_content, rate_mail_fail_from, invoice_cc, payment_from_cc, lowbalance_cc, alert_email_cc, auto_summary_cc, auto_balance_cc, auto_cdr_cc, send_cdr_cc, payment_sent_cc, payment_received_cc, rate_from, rate_subject, rate_content, dialer_detection_subject, dialer_detection_content, retrieve_password_subject, retrieve_password_content, retrieve_password_from, registration_subject, registration_content, registration_from, registration_success, registration_failure, trunk_change_from, trunk_change_subject, trunk_change_content, fraud_detection_from, fraud_detection_subject, fraud_detection_content, welcom_from, welcom_subject, welcom_content, download_rate_notice_from, download_rate_notice_subject, download_rate_notice_content, no_download_rate_from, no_download_rate_subject, no_download_rate_content, download_cdr_from, download_cdr_subject, download_cdr_content, download_cdr_cc, vendor_invoice_dispute_from, vendor_invoice_dispute_subject, vendor_invoice_dispute_content, vendor_invoice_dispute_cc, trunk_interop_from, trunk_interop_subject, trunk_interop_content, trunk_interop_cc, regletter_from, regletter_subject, regletter_content, regletter_cc, paymresvd_from, paymresvd_subject, paymresvd_content, paymresvd_cc, regconf_from, regconf_subject, regconf_content, regconf_cc, daily_payment_from, daily_payment_subject, daily_payment_content, daily_payment_cc, zerobalance_content, zerobalance_from, zerobalance_cc, zerobalance_subject, trunk_change_cc) FROM stdin;
\.


--
-- Data for Name: origination_global_failover; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY origination_global_failover (id, failover_strategy, from_sip_code, to_sip_code, to_sip_string) FROM stdin;
54	3	300	300	Multiple Choices
55	2	302	302	Moved Temporarily
56	2	305	305	Use Proxy
57	2	400	400	Bad Request
58	2	401	401	Unauthorized
59	2	402	402	Payment Required
60	2	403	403	Forbidden
61	2	404	404	Not Found
62	2	405	405	Method Not Allowed
63	2	406	406	Not Acceptable
64	2	407	407	Proxy Authentication Required
65	2	408	408	Request Timeout
66	2	409	409	Conflict
67	2	410	410	Gone
68	2	411	411	Length Required
69	2	412	412	Precondition Failed
106	2	380	380	Alternative Service
70	2	413	413	Request Entity Too Large
71	2	414	414	Request-URI Too Long
72	1	415	415	Unsupported Media Type
73	2	416	416	Unsupported URI Scheme
74	2	417	417	Unknown Resource-Priority
75	2	420	420	Bad Extension
76	2	421	421	Extension Required
77	2	422	422	Session Interval Too Small
78	2	423	423	Interval Too Brief
79	1	480	480	Temporarily Unavailable
80	1	481	481	Transaction Does Not Exist
81	1	482	482	Loop Detected
82	1	483	483	Too Many Hops
83	1	484	484	Address Incomplete
84	1	485	485	Ambiguous
85	3	486	486	Busy Here
86	3	487	487	Request Terminated
87	1	488	488	Not Acceptable Here
88	1	489	489	Bad Event
89	2	490	490	Request Updated
90	2	491	491	Request Pending
91	2	493	493	Undecipherable
92	2	494	494	Security Agreement Required
93	1	500	500	Internal Server Error
94	1	501	501	Not Implemented
95	1	502	502	Bad Gateway
96	3	503	503	Service Unavailable
97	1	504	504	Gateway Time-out
98	1	505	505	Version Not Supported
99	1	513	513	Message Too Large
100	1	580	580	Precondition Failure
101	2	600	600	Busy Everywhere
102	2	603	603	Decline
103	2	604	604	Does Not Exist Anywhere
104	2	606	606	Not Acceptable
105	1	687	687	Dialog Terminated
\.


--
-- Name: origination_global_failover_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('origination_global_failover_id_seq', 106, true);


--
-- Data for Name: payment_term; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY payment_term (payment_term_id, name, type, days, grace_days, notify_days, more_days, finance_rate) FROM stdin;
1	7-1	3	1	1	1	\N	\N
2	1-1	1	1	1	1	\N	\N
3	montly	1	16	3	3	\N	\N
\.


--
-- Data for Name: scheduler; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY scheduler (id, name, active, minute_type, minute, hour_type, hour, day_type, day, week, last_run, script_name) FROM stdin;
1	FTP CDR	f	\N	\N	\N	15	\N	\N	\N	\N	class4_ftp_cdr.pl
3	Unblock	f	\N	\N	\N	\N	\N	\N	\N	\N	block_log.php
\.


--
-- Data for Name: sys_module; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY sys_module (id, module_name, order_num, status) FROM stdin;
6	Configuration	1	1
11	Finance	3	1
14	Log	4	1
1	Management	5	1
7	Monitoring	6	1
13	Origination	7	1
4	Routing	8	1
2	Statistics	9	1
5	Switch	10	1
36	Template	11	1
3	Tools	13	1
37	Agent	12	1
\.


--
-- Name: sys_module_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('sys_module_id_seq', 38, true);


--
-- Data for Name: sys_pri; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY sys_pri (id, pri_name, module_id, pri_val, flag, pri_url) FROM stdin;
274	wizard	13	Wizard	t	did/wizard
276	us_ocn_lata	5	US OCN/LATA	t	us_ocn_lata/index
277	log	14	Origination Log	t	did/log/index
21	sysrolepris	6	Role	t	sysrolepris/view_sysrolepri
52	rates	5	Rate Table	t	rates/rates_list
50	jurisdictionprefixs	5	Jurisdiction	t	jurisdictionprefixs/view
49	paymentterms	5	Payment Term	t	paymentterms/payment_term
20	sysmodules	6	System Modules	t	sysmodules/view_sysmodule
59	alerts:rules	7	Rule	t	alerts/rules
41	gatewaygroups:view	4	Trunks	t	prresource/gatewaygroups/view_egress
45	routestrategys	4	Routing Plan	t	routestrategys/strategy_list
44	blocklists	4	Block List	t	blocklists/index
42	dynamicroutes	4	Dynamic Routing	t	dynamicroutes/view
1	clients	1	Carrier	t	clients/index
78	trunks	4	Trunks Registration	t	
25	transactions	11	Mutual Transaction	t	transactions/mutual
56	mailtmps	6	Mail Template	t	mailtmps/mail
71	downloads	5	Downloads	t	
73	fsconfigs	4	Disconnect Code	t	
54	timeprofiles	5	Time Profile	t	timeprofiles/profile_list
55	currs	5	Currency	f	currs/index
53	codedecks	5	Code Deck	t	codedecks/codedeck_list
16	import_export_log:export	14	Export Log	t	import_export_log/export
129	pr_invoices	11	Invoice	t	pr/pr_invoices/view
57	alerts:condition	7	Condition	t	
138	ajaxvalidates	4	Add trunk prefix	t	
15	import_export_log:import	14	Import Log	t	import_export_log/import
43	products	4	Static Routing	t	products/product_list
66	syspris	6	Syspris	t	
68	clientrates	5	Clientrates	t	
17	actual_transaction	11	Actual Transaction	t	transactions/actual
67	clientpayments	11	Recalculate Balance	t	
70	uploads	5	Uploads	t	
90	invoice_notification_log	11	Invoice Notification Log	t	finances/invoice_notification_log
91	view_past_due_log	11	Past Due Notification Log	t	finances/past_due_log
95	wizards	1	Wizard	t	wizards/index
40	digits	4	Digital Translation	t	digits/view
122	systemparams:failover	6	Fail-over Rule	t	systemparams/failover
124	systemparams:allow_cdr_fields	6	Carrier Portal CDR Fields	t	systemparams/allow_cdr_fields
125	users	6	Users	t	users/index
134	alerts:action	7	Action	t	
142	systemparams	6	System Setting	t	systemparams/view
143	server_config	5	VoIP Gateway	t	server_config
148	systemparams:invoice_setting	6	Invoice Setting	t	systemparams/invoice_setting
112	rate_log:import	14	Rate Import log	t	rate_log/import
119	logging	14	Modification Log 	t	logging
176	mail_sender	6	Mail Sender	t	mail_sender
160	billing_rule	13	Billing Rule	t	did/billing_rule
177	vendors	13	Vendors	t	did/vendors
161	alerts::block_ani	7	Block	t	
170	invoice_cdr_log	14	Invoice CDR Log	t	invoice_cdr_log
118	email_log	14	Email Log	t	email_log
77	dips	2	LNP Dip Record	t	dips
83	monitors	2	Monitors	t	
131	lrnreports	2	LRN Report	t	lrnreports
157	payment_history	14	Auto Payment Log	t	payment_history
151	auto_cdr_fields_setting	6	CDR Generation Format	t	systemparams/auto_cdr_fields_setting
179	clients_	13	Clients	t	did/clients
180	invoice_log	14	Invoice Log	t	pr/pr_invoices/invoice_log
193	credit_managements	1	Credit Management	t	credit_managements
154	systemparams:ftp_conf	6	FTP Job	t	systemparams/ftp_conf
9	mutual_statements	11	Past Due Summary	t	mutual_statements/summary_reports/
6	transactions:payment	11	Payment	t	transactions/payment
199	systemparams:ftp_log	14	Ftp Log	t	systemparams/ftp_log
200	systemparams:ftp_server_log	14	Ftp Server Log	t	systemparams/ftp_server_log
46	websessions	14	User Sign-on History	t	websessions/view
222	systemparams:payment_setting	6	Payment Setting	t	systemparams/payment_setting
51	systemlimits	5	Capacity	t	systemlimits/configuration
167	loop_detection	7	Loop Detection	t	loop_detection
245	balance_log	14	Balance Log	t	balance_log
247	credit_logs	14	Credit Log	t	credit_logs
235	logs:sql_log	14	SQL Log	t	logs/sql_log
265	logs:authorization_log	14	Authorization Log	t	logs/authorization_log
266	systemparams:global_route_error	6	Global Route Error	t	systemparams/global_route_error
267	ip_modify_log	14	IP Modify Log	t	ip_modify_log/index
268	logging:license_modification_log	14	License Modification Log	t	logging/license_modification_log
35	simulatedcalls	3	Call Simulation	t	simulatedcalls/simulated_call
69	stocks	2	Stocks	t	
271	task_scheduler:scheduler_log	14	Scheduler Log	t	task_scheduler/scheduler_log
159	active_calls:reports	2	Active Call Report	t	active_calls/reports
229	dashboard	2	Dashboard	t	homes/dashboard
256	cdrreports_db	2	CDRs List	t	cdrreports_db/summary_reports
255	cdrreports_db:spam	2	Spam Report	t	cdrreports_db/summary_reports/spam
257	usagedetails_db	2	Daily Usage Detail Report	t	usagedetails_db/orig_summary_reports
258	disconnectreports_db	2	Disconnect Causes	t	disconnectreports_db/summary_reports
259	reports_db:location	2	Location Report	t	reports_db/location
260	reports_db:usagereport	2	Usage Report	t	reports_db/usagereport
261	reports_db	2	Summary Report	t	reports_db/summary
262	reports_db:inout_report	2	Inbound/Outbound Report	t	reports_db/inout_report
263	reports_db:profit	2	Profitability Analysis	t	reports_db/profit
264	reports_db:qos_summary	2	QoS Summary	t	reports_db/qos_summary
226	pr:pr_invoices:carrier_invoice	11	Auto Invoice Management	t	pr/pr_invoices/carrier_invoice
269	rate_generation	3	Rate Generation	t	rate_generation/index
219	sip_packet	3	SIP Packet Search	t	cdrreports_db/sip_packet
297	products:route_info	13	Origination Static Routing	t	products/route_info
292	scheduled _report:scheduled_report_log	14	Scheduled Report Log	t	scheduled _report/scheduled_report_log
294	trunks:unclaimed_trunks	1	Unclaimed Trunks	t	trunks/unclaimed_trunks
296	rate_email_template	36	Rate Email Template	t	rate_email_template/index
298	usagedetails_db:daily_channel_usage_report	2	Daily Channel Usage Report	t	usagedetails_db/daily_channel_usage_report
300	retrieve_password_log	14	Retrieve Password Log	t	retrieve_password_log
287	random_ani	5	Random ANI Group	t	random_ani/random_table
301	template:resource	36	Ingress Trunk Template	t	template/resource
302	template	36	Egress Trunk Template	t	template/resource/1
304	registration	1	Registration	t	registration
305	finances:regenerate_balance	11	Regenerate Balance	t	finances/regenerate_balance
306	template:rate_upload_template	36	Rate Upload Template	t	template/rate_upload_template
299	carrier_template	36	Carrier Template	t	carrier_template/index
308	product_management	1	Product	t	product_management/index
310	detections:fraud_detection	7	Fraud Detection	t	detections/fraud_detection
311	rate_mass_edit_log	14	Rate Mass Edit Log	t	clientrates/rate_mass_edit_log
312	reports_db/host_based_report	2	Host Based Report	t	reports_db/host_based_report
141	did	13	DID Repository	t	did/did
275	clients/rate_summary	1	Client Rate Summary	t	clients/rate_summary
316	prresource/gatewaygroups/sip_register_log	14	SIP Register	t	prresource/gatewaygroups/sip_register_log
319	routestrategys/wizard 	4	Wizard 	t	routestrategys/wizard 
303	rerate	3	Rerate	f	rerate/index
323	trunk_group	6	Trunk Group	t	trunk_group/index
324	systemparams:login_page_content	6	Login Page Content	t	systemparams/login_page_content
\.


--
-- Name: sys_pri_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('sys_pri_id_seq', 324, true);


--
-- Data for Name: sys_role; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY sys_role (role_id, role_name, role_info, view_all, delete_invoice, delete_payment, delete_credit_note, delete_debit_note, reset_balance, modify_credit_limit, modify_min_profit, view_cost_and_rate) FROM stdin;
1	admin	\N	t	1	1	1	1	1	1	1	1
\.


--
-- Data for Name: system_parameter; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_parameter (sys_timezone, sys_area, mailserver_host, mail_server_from, de_pin_len, ftp_username, ftp_pass, date_format, datetime_format, sys_currency, sys_id, invoices_tplno, invoices_lastno, invoices_fields, invoices_delay, invoices_separate, invoices_cdr_fields, dr_period, radius_log_routes, events_notfoundaccount, events_notfoundtariff, events_unprofitable, events_alertszerotime, "lowBalance_period", events_deleteafterdays, stats_rotate_delay, rates_deleteafterdays, cdrs_deleteafterdays, logs_deleteafterdays, backup_period, backup_leave_last, csv_delimiter, sys_ani, conf_number, msgmonthlyfee, fail_calls, forbidden_times, conf_max_duration, smtphost, smtpport, emailusername, emailpassword, fromemail, emailname, loginemail, system_admin_email, switch_ip, switch_port, noc_email, finance_email, pdf_tpl, tpl_number, rate_clean_days, smtp_secure, default_code_deck, qos_sample_period, minimal_call_attempt_required, low_call_attempt_handling, welcome_message, report_count, realm, workstation, landing_page, invoice_name, auto_delivery_timezone, auto_delivery_address, allow_cdr_fields, company_info, bar_color, auto_delivery_group_by, inactivity_timeout, is_preload, yourpay_store_number, paypal_account, withdraw_email, switch_alias, overlap_invoice_protection, send_cdr_fields, system_rate_mail, ingress_pdd_timeout, egress_pdd_timeout, ring_timeout, call_timeout, invoice_send_mode, company_info_location, daily_payment_confirmation, daily_payment_email, notify_carrier, notify_carrier_cc, payment_setting_subject, payment_content, is_show_mutual_balance, ftp_email, is_hide_unauthorized_ip, stripe_account, require_comment, auto_rate_smtp, auto_rate_username, auto_rate_pwd, auto_rate_smtp_port, auto_rate_mail_ssl, themer, default_us_ij_rule, report_hourly_save_days, report_daily_save_days, report_code_save_days, full_cdr_save_days, simple_cdr_save_days, non_zero_cdr_save_days, invoice_decimal_digits, stripe_public_account, default_billing_decimal, auto_carrier_notification, login_page_content, payment_received_confirmation, paypal_service_charge, stripe_service_charge, payment_from, payment_subject, payment_from_cc, login_fit_image, cdr_token, cdr_token_alias, cmd_debug, signup_content, charge_type, login_captcha, paypal_test_mode, base_url) FROM stdin;
+0000	\N	\N	\N	\N	\N	\N	\N	\N	1	1	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N							true		\N	\N			\N	0	\N	0	0	\N	\N	\N		0			0		+00	\N	\N	\N		0	0	f		\N			t	\N	\N	6000	6000	60	3600	0	0	\N	\N	\N	\N	\N	\N	0		0	\N	0				0	0	0	0	30	180	60	30	180	60	2	\N	6	\N	\N	\N	0	0	\N	\N	\N	\N					0	\N	\N	/
\.


--
-- Name: system_parameter_sys_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_parameter_sys_id_seq', 1, true);


--
-- Data for Name: termination_global_failover; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY termination_global_failover (id, failover_strategy, from_sip_code, to_sip_code, to_sip_string) FROM stdin;
5	1	400	400	Bad Request
6	1	401	401	Unauthorized
7	1	402	402	Payment Required
8	1	403	403	Forbidden
9	3	404	404	Not Found
10	1	405	405	Method Not Allowed
11	1	406	406	Not Acceptable
12	1	407	407	Proxy Authentication Required
13	3	408	408	Request Timeout
14	1	409	409	Conflict
15	2	410	410	Gone
16	2	411	411	Length Required
17	2	412	412	Precondition Failed
18	2	413	413	Request Entity Too Large
19	2	414	414	Request-URI Too Long
20	2	415	415	Unsupported Media Type
21	2	416	416	Unsupported URI Scheme
22	2	417	417	Unknown Resource-Priority
23	2	420	420	Bad Extension
24	2	421	421	Extension Required
25	2	422	422	Session Interval Too Small
26	2	423	423	Interval Too Brief
27	1	480	480	Temporarily Unavailable
28	1	481	481	Transaction Does Not Exist
29	1	482	482	Loop Detected
30	1	483	483	Too Many Hops
31	1	484	484	Address Incomplete
32	1	485	485	Ambiguous
33	3	486	486	Busy Here
34	1	488	488	Not Acceptable Here
35	1	489	489	Bad Event
36	2	490	490	Request Updated
37	2	491	491	Request Pending
38	2	493	493	Undecipherable
39	2	494	494	Security Agreement Required
40	1	500	500	Internal Server Error
41	1	501	501	Not Implemented
42	1	502	502	Bad Gateway
43	3	503	503	Service Unavailable
44	1	504	504	Gateway Time-out
45	1	505	505	Version Not Supported
46	1	513	513	Message Too Large
47	1	580	580	Precondition Failure
48	2	600	600	Busy Everywhere
49	2	603	603	Decline
50	2	604	604	Does Not Exist Anywhere
51	2	606	606	Not Acceptable
52	2	687	687	Dialog Terminated
53	1	487	487	Request Terminated
1	2	302	302	Moved Temporarily
2	2	305	305	Use Proxy
3	2	380	380	Alternative Service
4	3	300	301	Multiple Choices
\.


--
-- Name: termination_global_failover_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('termination_global_failover_id_seq', 53, true);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY users (user_id, name, password, role_id, reseller_id, create_time, active, client_id, email, fullname, user_type, create_user_id, last_login_time, card_id, is_online, login_ip, default_mod, default_mod2, last_seen, report_group, outbound_report, all_termination, show_carrier_trunk_drop_only, report_fields) FROM stdin;
1	admin	e10adc3949ba59abbe56e057f20f883e	1	\N	2012-12-05 09:19:15+00	t	\N	\N	\N	1	\N	2017-09-08 12:33:02+00	\N	2	119.139.196.208	0	0	2017-09-08 12:37:14+00	t	t	t	f	\N
\.


insert into sys_pri (pri_name, module_id, pri_val, flag, pri_url)
values( 'did_report', 13, 'DID Report', 't', 'cdrapi/did_report'),
('orig_invoice', 13, 'Invoice', 't', 'did/orig_invoice/view/'),
('agent:management', 37, 'Agent Management', 't', 'agent/management'),
('reports_db:commission', 37, 'Commission Report', 't', 'reports_db/commission');

insert into version_information (program_name, major_ver, minor_ver, build_date, start_time) values ('database_version', 'V5.2.20181025', '2df1fa28374399273fddb8bd90c901951dd70531', '2018-10-25', DEFAULT);

--
-- Name: users_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_user_id_seq', 8, true);


--
-- Name: voip_gateway_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('voip_gateway_id_seq', 2, true);


--
-- PostgreSQL database dump complete
--

