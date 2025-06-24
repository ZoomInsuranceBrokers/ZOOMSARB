<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'Bank',
        'Account_No',
        'IFSC_Code',
        'Branch',
    ];
}
