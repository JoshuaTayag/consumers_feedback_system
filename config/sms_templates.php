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
            . "Your request is currently under review. You will receive another SMS once it has been scheduled for implementation.",
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
            . "Our authorized field personnel will visit the service location. Please ensure safe and convenient access to your electric meter.",
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
            . "Thank you for choosing LEYECO V. We appreciate the opportunity to serve you.",
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
            . "Thank you for choosing LEYECO V. If you wish to proceed with your Change Meter Request, please contact your nearest LEYECO V office to arrange a new schedule.",
    ],
];