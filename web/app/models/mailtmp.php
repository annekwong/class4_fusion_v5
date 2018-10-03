<?php

class Mailtmp extends AppModel
{

    var $name = 'Mailtmp';
    var $useTable = 'mail_tmplate';
    var $primaryKey = 'id';

    var $mail_template_arr = array();

    public function beforeSave($options = array())
    {
        $existsRecordCount = $this->find('count');

        if ($existsRecordCount == 0) {
            unset($this->data['Mailtmp']['id']);
        }

        return true;
    }

    public function get_mail_senders()
    {
        $sql = "select * from mail_sender order by email asc";
        return $this->query($sql);
    }

    public function get_mail_template_arr()
    {
        $this->mail_template_arr = array(
            array(
                'title' => __('Welcome Letter', true),
                'from_email' => 'welcom_from',
                'subject' => 'welcom_subject',
                'content' => 'welcom_content',
                'cc' => 'welcom_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'client_name', 'username', 'login_url'
                ),
            ),
            array(
                'title' => __('retrievepasswordmailtemp', true),
                'from_email' => 'retrieve_password_from',
                'subject' => 'retrieve_password_subject',
                'content' => 'retrieve_password_content',
                'cc' => 'retrieve_password_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'client_name', 'username', 'url'
                ),
            ),
            array(
                'title' => __('invoiceemailtemp', true),
                'from_email' => 'invoice_from',
                'subject' => 'invoice_subject',
                'content' => 'invoice_content',
                'cc' => 'invoice_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'client_name', 'invoice_amount', 'invoice_number', 'cdr_url', 'invoice_link', 'start_date', 'end_date'
                ),
            ),
            array(
                'title' => __('paymentnotifytemp', true),
                'from_email' => 'payment_from',
                'subject' => 'payment_subject',
                'content' => 'payment_content',
                'cc' => 'payment_from_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'amount', 'receiving_time', 'client_name'
                ),
            ),
            array(
                'title' => __('lowbalancetemp', true),
                'from_email' => 'lowbalance_from',
                'subject' => 'lowbalance_subject',
                'content' => 'lowbalance_content',
                'cc' => 'lowbalance_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'current_date', 'balance', 'payment_terms', 'credit_limit', 'remaining_credit'
                ),
            ),
            array(
                'title' => "ZERO balance notification",
                'from_email' => 'zerobalance_from',
                'subject' => 'zerobalance_subject',
                'content' => 'zerobalance_content',
                'cc' => 'zerobalance_cc',
                'header_tags' => array(),
                'tags' => array(
                    'date', 'company_name', 'client_name', 'notify_balance', 'balance', 'allow_credit', 'payment_terms'
                ),
            ),
//            array(
//                'title' => __('Alert Email',true),
//                'from_email' => 'alert_email_from',
//                'subject'   => 'alert_email_subject',
//                'content' => 'alert_email_content',
//                'cc' => 'alert_email_cc',
//                'header_tags' => array(),
//                'tags' => array(
//                    'company_name','client_name','Code Name','ASR','ACD','TT Number'
//                ),
//            ),
            array(
                'title' => __('Daily Summary Email Template', true),
                'from_email' => 'auto_summary_from',
                'subject' => 'auto_summary_subject',
                'content' => 'auto_summary_content',
                'cc' => 'auto_summary_cc',
                'header_tags' => array(),
                'tags' => array(
                    'client_name', 'company_name', 'begin_time', 'end_time', 'customer_gmt', 'total_call_buy', 'total_not_zero_calls_buy', 'total_success_call_buy', 'total_billed_min_buy', 'total_billed_amount_buy', 'credit_limit',
                    'remaining_credit', 'balance', 'total_call_sell', 'total_not_zero_calls_sell', 'total_success_call_sell', 'total_billed_min_sell', 'total_billed_amount_sell', 'buy_total_duration', 'sell_total_duration', 'switch_alias'
                ),
            ),
            array(
                'title' => __('Daily Balance Email Template', true),
                'from_email' => 'auto_balance_from',
                'subject' => 'auto_balance_subject',
                'content' => 'auto_balance_content',
                'cc' => 'auto_balance_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'client_name', 'start_time', 'end_time', 'beginning_balance', 'buy_amount',
                    'sell_amount', 'ending_balance', 'allowed_credit', 'remaining_credit', 'begining_of_day',
                    'current_time', 'current_day', 'beginning_of_day_balance', 'current_balance', 'credit_limit',
                    'balance'
                ),
            ),

            array(
                'title' => __('Daily CDR Email Template', true),
                'from_email' => 'auto_cdr_from',
                'subject' => 'auto_cdr_subject',
                'content' => 'auto_cdr_content',
                'cc' => 'auto_cdr_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'client_name', 'begin_time', 'end_time', 'customer_gmt', 'cdr_count', 'download_link', 'current_day'
                ),
            ),
            array(
                'title' => __('Payment Received Email Template', true),
                'from_email' => 'payment_received_from',
                'subject' => 'payment_received_subject',
                'content' => 'payment_received_content',
                'cc' => 'payment_received_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'amount', 'receiving_time', 'client_name'
                ),
            ),
            array(
                'title' => __('Notice of Receipt Template', true),
                'from_email' => 'rate_mail_success_from',
                'subject' => 'rate_mail_success_subject',
                'content' => 'rate_mail_success_content',
                'cc' => 'rate_mail_success_cc',
                'header_tags' => array(),
                'tags' => array(
                    'client_name', 'company_name'
                ),
            ),
//            array(
//                'title' => __('Notice of Rate Upload Failure',true),
//                'from_email' => 'rate_mail_fail_from',
//                'subject'   => 'rate_mail_fail_subject',
//                'content' => 'rate_mail_fail_content',
//                'cc' => 'rate_mail_fail_cc',
//                'header_tags' => array(),
//                'tags' => array(
//                    'client_name','company_name'
//                ),
//            ),
            array(
                'title' => __('Notice of Trunk Change Template', true),
                'from_email' => 'trunk_change_from',
                'subject' => 'trunk_change_subject',
                'content' => 'trunk_change_content',
                'cc' => 'trunk_change_cc',
                'header_tags' => array(
                    'company_name', 'client_name', 'trunk_name'
                ),
                'tags' => array(
                    'company_name', 'client_name', 'username', 'company', 'trunk_name', 'detail_table'
                ),
            ),
            array(
                'title' => __('Rate is Downloaded Notification', true),
                'from_email' => 'download_rate_notice_from',
                'subject' => 'download_rate_notice_subject',
                'content' => 'download_rate_notice_content',
                'cc' => 'download_rate_notice_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'client_name', 'download_ip', 'rate_download_link', 'download_time', 'trunk_name', 'prefix'
                ),
            ),
            array(
                'title' => __('Suspended Trunk Due to Rate Not Downloaded', true),
                'from_email' => 'no_download_rate_from',
                'subject' => 'no_download_rate_subject',
                'content' => 'no_download_rate_content',
                'cc' => 'no_download_rate_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'trunk_name', 'effective_date', 'rate_update_file_name', 'rate_download_deadline'
                ),
            ),
            array(
                'title' => __('48 Hour Suspension Warning', true),
                'from_email' => 'hour_from_48',
                'subject' => 'hour_subject_48',
                'content' => 'hour_content_48',
                'cc' => 'hour_cc_48',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'trunk_name', 'effective_date', 'rate_update_file_name', 'rate_download_deadline', 'download_url'
                ),
            ),
            array(
                'title' => __('24 Hour Suspension Warning', true),
                'from_email' => 'hour_from_24',
                'subject' => 'hour_subject_24',
                'content' => 'hour_content_24',
                'cc' => 'hour_cc_24',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'trunk_name', 'effective_date', 'rate_update_file_name', 'rate_download_deadline', 'download_url'
                ),
            ),
            array(
                'title' => __('3 Hour Suspension Warning', true),
                'from_email' => 'hour_from_3',
                'subject' => 'hour_subject_3',
                'content' => 'hour_content_3',
                'cc' => 'hour_cc_3',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'trunk_name', 'effective_date', 'rate_update_file_name', 'rate_download_deadline', 'download_url'
                ),
            ),
            array(
                'title' => __('1 Hour Suspension Warning', true),
                'from_email' => 'hour_from_1',
                'subject' => 'hour_subject_1',
                'content' => 'hour_content_1',
                'cc' => 'hour_cc_1',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'trunk_name', 'effective_date', 'rate_update_file_name', 'rate_download_deadline', 'download_url'
                ),
            ),
            array(
                'title' => __('Send Downloaded CDR', true),
                'from_email' => 'download_cdr_from',
                'subject' => 'download_cdr_subject',
                'content' => 'download_cdr_content',
                'cc' => 'download_cdr_cc',
                'header_tags' => array(),
                'tags' => array(
                    'download_link',
                    'username'
                ),
            ),
//            array(
//                'title' => __('Vendor Invoice Dispute Note Template',true),
//                'from_email' => 'vendor_invoice_dispute_from',
//                'subject'   => 'vendor_invoice_dispute_subject',
//                'content' => 'vendor_invoice_dispute_content',
//                'cc' => 'vendor_invoice_dispute_cc',
//                'header_tags' => array(),
//                'tags' => array(
//                    'company_name','client_name','dispute_value','carrier_name','billing_duration'
//                ),
//            ),
//            array(
//                'title' => __('Vendor Invoice Dispute Note Template',true),
//                'from_email' => 'vendor_invoice_dispute_from',
//                'subject'   => 'vendor_invoice_dispute_subject',
//                'content' => 'vendor_invoice_dispute_content',
//                'cc' => 'vendor_invoice_dispute_cc',
//                'header_tags' => array(),
//                'tags' => array(
//                    'dispute_value','carrier_name','billing_duration'
//                ),
//            ),
            array(
                'title' => __('Trunk Interop Template', true),
                'from_email' => 'trunk_interop_from',
                'subject' => 'trunk_interop_subject',
                'content' => 'trunk_interop_content',
                'cc' => 'trunk_interop_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'client_name', 'trunk_name', 'carrier_name', 'trunk_type', 'route_info', 'IP_listing'
                ),
            ),
            array(
                'title' => __('Registration Confirmation Content', true),
                'from_email' => 'regconf_from',
                'subject' => 'regconf_subject',
                'content' => 'regconf_content',
                'cc' => 'regconf_cc',
                'header_tags' => array(),
                'tags' => array(
                    'first_name'
                ),
            ),
            array(
                'title' => __('Registration Letter', true),
                'from_email' => 'regletter_from',
                'subject' => 'regletter_subject',
                'content' => 'regletter_content',
                'cc' => 'regletter_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'client_name', 'username', 'email', 'login_url'
                ),
            ),
            array(
                'title' => __('Pending Trunk Suspension Notice', true),
                'from_email' => 'pending_trunk_from',
                'subject' => 'pending_trunk_subject',
                'content' => 'pending_trunk_content',
                'cc' => 'pending_trunk_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'trunk_name', 'effective_date', 'rate_update_file_name', 'rate_download_deadline'
                ),
            ),
            array(
                'title' => __('DID Order Letter', true),
                'from_email' => 'did_order_from',
                'subject' => 'did_order_subject',
                'content' => 'did_order_content',
                'cc' => 'did_order_cc',
                'header_tags' => array(),
                'tags' => array(
                    'company_name', 'did', 'setup_fee', 'monthly_fee', 'per_min_rate'
                ),
            ),
        );
        return $this->mail_template_arr;
    }

    /*
     * 判断是否有没有配置的选项
     * return true  :有需要配置的选项为空
     */
    public function check_mail_tmp()
    {
        $conditions = array(
            "invoice_subject is not null",
            "invoice_content is not null",
            "payment_subject is not null",
            "payment_content is not null",
            "lowbalance_subject is not null",
            "lowbalance_content is not null",
            "alert_email_subject is not null",
            "alert_email_content is not null",
            "auto_summary_subject is not null",
            "auto_summary_content is not null",
            "auto_balance_subject is not null",
            "auto_balance_content is not null",
            "auto_cdr_subject is not null",
            "auto_cdr_content is not null",
            "payment_sent_subject is not null",
            "payment_sent_content is not null",
            "payment_received_subject is not null",
            "payment_received_content is not null",
            "rate_mail_success_subject is not null",
            "rate_mail_success_content is not null",
            "rate_mail_fail_subject is not null",
            "rate_mail_fail_content is not null",
            "invoice_subject != ''",
            "invoice_content != ''",
            "payment_subject != ''",
            "payment_content != ''",
            "lowbalance_subject != ''",
            "lowbalance_content != ''",
            "alert_email_subject != ''",
            "alert_email_content != ''",
            "auto_summary_subject != ''",
            "auto_summary_content != ''",
            "auto_balance_subject != ''",
            "auto_balance_content != ''",
            "auto_cdr_subject != ''",
            "auto_cdr_content != ''",
            "payment_sent_subject != ''",
            "payment_sent_content != ''",
            "payment_received_subject != ''",
            "payment_received_content != ''",
            "rate_mail_success_subject != ''",
            "rate_mail_success_content != ''",
            "rate_mail_fail_subject != ''",
            "rate_mail_fail_content != ''",

        );
        $condition_str = implode(" and ", $conditions);
        $sql = "select count(*) as sum from mail_tmplate where {$condition_str}";
        $data = $this->query($sql);
        if ($data[0][0]['sum'])
            return false;
        else
            return true;
    }

}

?>
