<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdminActivityLog extends Model
{
    public $timestamps = false;

    protected $table = 'superadmin_activity_logs';

    protected $fillable = [
        'super_admin_id',
        'action',
        'ip_address',
        'user_agent',
        'created_at',
    ];
}
