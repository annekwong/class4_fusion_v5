<?php 
App::import('Model','PaymentTerm');
class AppPaymenttermsHelper extends AppHelper {	
	function get_options_type(){
		$options_type=Array(
			PaymentTerm::PAYMENT_TERM_DAY_OF_DAYS_SEPARATED=>__('Day of month',true),
			PaymentTerm::PAYMENT_TERM_DAY_OF_EACH_MONTH=>__('Every',true),
			PaymentTerm::PAYMENT_TERM_DAY_OF_EACH_WEEK=>__('Day of Week',true),
			PaymentTerm::PAYMENT_TERM_SOME_DAY_OF_DAYS_SEPARATED=>__('Some Day of month',true),
			PaymentTerm::PAYMENT_TERM_TWICE_IN_A_MONTH=>__('Twice In a Month',true)
		);
		return $options_type;
	}
}
?>
