<?php

// namespace App\Imports;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportUser  extends Model
{

    public function model(array $row)
    {
        $data = [
            'mgt_no' =>  $row['mgt_no'],
            'password' =>  $row['password'],
            'department' =>  $row['department'],
            'furigana' =>  $row['furigana'],
            'family_name' =>  $row['family_name'],
            'first_name' =>  $row['first_name'],
            'region' =>  $row['region'],
            // New Addition start
            'email' =>  $row['email'],
            'email2' =>  $row['email2'],
            'official_registration_date' =>  $row['official_registration_date'],
            'birthday' =>  $row['birthday'],
            'sex' =>  $row['sex'],
            'postal_code' =>  $row['postal_code'],
            'current_address' =>  $row['current_address'],
            'desired_subject' =>  $row['desired_subject'],
            'preferred_working_style' =>  $row['preferred_working_style'],
            'current_address_area' =>  $row['current_address_area'],
            'desired_area' =>  $row['desired_area'],
            'content' =>  $row['content'],
            'last_updated' =>  $row['last_updated'],
        ];
        return new User($data);
    }
}
