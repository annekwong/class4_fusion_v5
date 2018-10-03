--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.4
-- Dumped by pg_dump version 9.5.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Data for Name: voip_gateway; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY voip_gateway (id, name, paid_replace_ip, lan_ip, lan_port, active_call_ip, active_call_port, sip_capture_ip, sip_capture_port, sip_capture_path) FROM stdin;
2	class4	0	127.0.0.1	4320	127.0.0.1	4305	\N	\N	\N
\.


--
-- Data for Name: switch_profile; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY switch_profile (id, switch_name, profile_name, sip_ip, sip_port, sip_debug, sip_trace, proxy_ip, proxy_port, voip_gateway_id, support_rpid, support_oli, support_priv, support_div, support_paid, support_pci, support_x_lrn, support_x_header, sip_capture_ip, sip_capture_port, sip_capture_path, lan_ip, lan_port, profile_status, paid_replace_ip, auth_register, default_register, report_ip, report_port, active_call_ip, active_call_port, cps, cap, pcap_token) FROM stdin;
2	class4	class4	192.99.10.113	5060	0	f		\N	2	0	0	0	0	0	0	0	0	\N	\N	\N	127.0.0.1	4320	\N	0	0	\N	\N	3300	127.0.0.1                                                       	4305	\N	\N	
3	class4	class4_new	192.99.10.113	5080	0	f		\N	2	0	0	0	0	0	0	0	0	\N	\N	\N	127.0.0.1	4320	\N	0	1	\N	\N	3300	127.0.0.1                                                       	4305	\N	\N	
\.


--
-- Name: switch_profile_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('switch_profile_id_seq', 3, true);


--
-- Name: voip_gateway_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('voip_gateway_id_seq', 2, true);


--
-- PostgreSQL database dump complete
--

