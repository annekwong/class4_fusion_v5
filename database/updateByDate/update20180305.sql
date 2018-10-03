drop table rate_bot_import_logs;

--
-- Name: rate_bot_import_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE rate_bot_import_logs (
    id integer NOT NULL,
    status  int default 0,
	email_subject varchar(100),
	email_from varchar(100),
	email_time varchar(100),
	start_time   timestamp with time zone DEFAULT now(),
	finish_time  timestamp with time zone ,
	rule_name varchar(100),
	error_msg varchar(200),
	mail_vendor varchar(128),
	mail_client varchar(128)
);


ALTER TABLE rate_bot_import_logs OWNER TO postgres;

--
-- Name: rate_bot_import_logs_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE rate_bot_import_logs_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE rate_bot_import_logs_seq OWNER TO postgres;

--
-- Name: rate_bot_import_logs_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE rate_bot_import_logs_seq OWNED BY rate_bot_import_logs.id;

--
-- Name: rate_bot_import_logs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rate_bot_import_logs ALTER COLUMN id SET DEFAULT nextval('rate_bot_import_logs_seq'::regclass);

--
-- Name: rate_bot_import_logs rate_bot_import_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rate_bot_import_logs
    ADD CONSTRAINT rate_bot_import_logs_pkey PRIMARY KEY (id);


UPDATE version_information SET major_ver = 'V5.0.0', build_date = '2018-03-05' WHERE program_name = 'dnl_softswitch';
UPDATE version_information SET major_ver = 'V5.2.20180305', build_date = '2018-03-05' WHERE program_name = 'database_version';