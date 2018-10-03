--
-- Name: client_taxes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE client_taxes (
    id integer NOT NULL,
	client_id integer NOT NULL,
	tax_name varchar(255) NOT NULL,
	tax_percent numeric NOT NULL
);


ALTER TABLE client_taxes OWNER TO postgres;

--
-- Name: client_taxes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE client_taxes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE client_taxes_id_seq OWNER TO postgres;

--
-- Name: client_taxes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE client_taxes_id_seq OWNED BY client_taxes.id;

--
-- Name: client_taxes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY client_taxes ALTER COLUMN id SET DEFAULT nextval('client_taxes_id_seq'::regclass);