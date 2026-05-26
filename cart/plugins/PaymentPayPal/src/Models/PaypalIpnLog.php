<?php

namespace Plugins\PaymentPayPal\Models;

use Illuminate\Database\Eloquent\Model;

class PaypalIpnLog extends Model
{
    protected $table = 'paypal_ipn_logs';
    protected $fillable = [
        'ipn_log_id',
        'raw_post_data',
        'response_status',
        'response_data',
        'processed',
    ];

    protected $casts = [
        'processed' => 'boolean',
        'response_data' => 'array',
    ];
}
