<?php
// config/sms_templates.php

return [

    'request_received' => [
        'label' => 'Request Received',
        'body'  => "Your Change Meter Request has been successfully recorded.\n\n"
            . "Ref. No.: {CONTROL_NO}\n"
            . "Account No.: {ACCOUNT_NO}\n"
            . "Account Name: {ACCOUNT_NAME}\n"
            . "Service Address: {ADDRESS}\n"
            . "Date Filed: {REQUEST_DATE}\n\n"
            . "Your request is currently under review. You will receive another SMS once it has been scheduled for implementation.\n\n"
            . "Please be informed that this meter replacement service is completely FREE OF CHARGE.\n\n"
            . "LEYECO V: Pag-alagad na madasigon",
    ],

    'field_personnel_dispatched' => [
        'label' => 'Field Personnel Dispatched',
        'body'  => "Your Change Meter Request has been officially dispatched to our field personnel for on-site implementation.\n\n"
            . "Ref. No.: {CONTROL_NO}\n"
            . "Account No.: {ACCOUNT_NO}\n"
            . "Account Name: {ACCOUNT_NAME}\n"
            . "Service Address: {ADDRESS}\n"
            . "Date Dispatched: {DISPATCH_DATE}\n"
            . "Crew: {CM_CREW}\n\n"
            . "Our authorized field personnel will visit the service location. Please ensure safe and convenient access to your electric meter."
            . "Please be informed that this meter replacement service is completely FREE OF CHARGE.\n\n"
            . "LEYECO V: Pag-alagad na madasigon",
    ],

    'request_completed' => [
        'label' => 'Request Completed',
        'body'  => "Your Change Meter Request for your account has been completed.\n\n"
            . "Ref. No.: {CONTROL_NO}\n"
            . "Account No.: {ACCOUNT_NO}\n"
            . "Account Name: {ACCOUNT_NAME}\n"
            . "Service Address: {ADDRESS}\n"
            . "Acknowledged By: {ACKNOWLEDGE_BY}\n"
            . "Completion Date: {COMPLETION_DATE}\n\n"
            . "For other queries, please contact our 24/7 Consumer Welfare Desk hotline at 0917-683-7230 or message our FB page: Leyte V Electric Cooperative, Inc.\n\n"
            . "LEYECO V: Pag-alagad na madasigon",
    ],

    'request_not_completed' => [
        'label' => 'Request Not Completed',
        'body'  => "The Change Meter Request for your account has not been completed.\n\n"
            . "Ref. No.: {CONTROL_NO}\n"
            . "Account No.: {ACCOUNT_NO}\n"
            . "Account Name: {ACCOUNT_NAME}\n"
            . "Service Address: {ADDRESS}\n"
            . "Date: {DATE_ACTED}\n\n"
            . "Reason: {REASON}\n\n"
            . "Thank you for choosing LEYECO V. If you wish to proceed with your Change Meter Request, please contact LEYECO V office, our 24/7 Consumer Welfare Desk Hotline at **0917-683-7230**, or our Facebook page, **Leyte V Electric Cooperative, Inc.**, to arrange a new schedule.",
    ],
];