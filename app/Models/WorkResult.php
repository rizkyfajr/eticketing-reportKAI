<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;

class WorkResult extends Model
{
    use HasFactory, HasAttachment;

    protected $table = 'work_results';

    protected $fillable = [
        'working_report_id',
        'machine_id',
        'region_id',
        'antara',
        'km_hm',
        'jumlah_msp',
        'waktu_start_engine',
        'jam_luncuran',
        'jam_kerja',
        'jam_mesin',
        'jam_genset',
        'counter_pecok',
        'oddometer',
        'penggunaan_hsd',
        'hsd_tersedia',
        'pengawal_id',
        'note',
        'created_by_id',
        'updated_by_id',
    ];

    protected $with = [
        'workingreport',
        'machine',
        'region',
        'pengawal',
        'createdBy',
        'updatedBy',
    ];

    public function workingreport()
    {
        return $this->belongsTo(WorkingReport::class, 'machine_id');
    }

    public function machine()
    {
        return $this->belongsTo(MasterMachine::class, 'machine_id');
    }

    public function region()
    {
        return $this->belongsTo(MasterRegion::class, 'region_id');
    }

    public function pengawal()
    {
        return $this->belongsTo(User::class, 'pengawal_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function reportUsers()
    {
        return $this->hasMany(ReportUser::class, 'report_id', 'id');
    }
}
