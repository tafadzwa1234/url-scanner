<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'email_subject',
        'email_sender',
        'is_malicious',
        'threat_details'
    ];

    protected $casts = [
        'is_malicious' => 'boolean',
    ];
} 