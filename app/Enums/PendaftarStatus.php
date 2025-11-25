<?php
namespace App\Enums;

enum PendaftarStatus: string
{
    case SUBMIT = 'SUBMIT';
    case ADM_PASS = 'ADM_PASS';
    case ADM_REJECT = 'ADM_REJECT';
    case PAID = 'PAID';
    case ACCEPTED = 'ACCEPTED';
    case REJECTED = 'REJECTED';
}