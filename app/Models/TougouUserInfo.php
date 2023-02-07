<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TougouUserInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'lastname',
        'firstname',
        'lastname_kana',
        'firstname_kana',
        'birthdate',
        'zip',
        'address1',
        'address2',
        
        'college_type',
        'college_name',
        'graduation_year',
        'medical_license_year',
        'medical_license_month',
        'medical_license_day',
        'desired_working_j',
        'desired_working_h',
        'desired_working_s',
        'desired_working_k',
        'j_location1',
        'j_location2',
        'j_location3',
        'h_location1',
        'h_location2',
        'h_location3',
        's_location1',
        's_location2',
        's_location3',
        'k_location1',
        'k_location2',
        'k_location3',

        'j_subject',
        'j_subject_others',
        'h_subject',
        'h_subject_others',
        's_subject',
        's_subject_others',
        
        'doctor_id',
        'birthplace',
        'sex',
        'kiboukamoku',
    ];

    public function user()
    {
        return $this->belongsTo(TougouUser::class, 'user_id');
    }
}
