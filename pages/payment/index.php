<?php

/**
 * @defgroup pages_payment Payment page
 */
 
/**
 * @file pages/payment/index.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @ingroup pages_payment
 * @brief Handle requests for interactions between the payment system and external
 * sites/systems.
 */

switch ($op) {
	case 'plugin':
		define('HANDLER_CLASS', 'PaymentHandler');
		import('pages.payment.PaymentHandler');
		break;
}


