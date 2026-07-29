<?php
// app/Enums/SmsTemplate.php

namespace App\Enums;

enum SmsTemplate: string
{
    case RequestReceived           = 'request_received';
    case FieldPersonnelDispatched  = 'field_personnel_dispatched';
    case RequestCompleted          = 'request_completed';
    case RequestNotCompleted       = 'request_not_completed';

    public function label(): string
    {
        return config("sms_templates.{$this->value}.label");
    }
}