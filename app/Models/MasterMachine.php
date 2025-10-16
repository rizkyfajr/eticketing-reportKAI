<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMachine extends Model
{
    use HasFactory;

    protected $table = 'master_machines';

    protected $fillable = [
        'code',
        'name',
        'type',
        'location',
        'status',
    ];
}
