<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'roll_no', 'class_id', 'father_name', 'phone', 'dob', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setFatherNameAttribute($value = null)
    {
        $this->attributes['father_name'] = ucwords(strtolower($value));
    }
    public static function formatRollno($value)
    {
        if (empty($value)) return null;
        $numberOnly = preg_replace('/[^0-9]/', '', $value);

        if (empty($numberOnly)) return null;

        return 'STU-' . $numberOnly;
    }
    public function setRollNoAttribute($value)
    {
        $this->attributes['roll_no'] = self::formatRollno($value);
    }

    protected $casts = [
        'dob' => 'date',
    ];
    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = date('Y-m-d', strtotime($value));
    }
}
