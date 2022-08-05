<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';
    public $timestamps = true;
    protected $primaryKey = 'created_at';
    protected $keyType = 'datetime';
    protected $fillable = [
        'address',
        'phone',
        'email',

        'inn',
        'kpp',
        'bik',
        'bank_name',
        'bank_num',
        'bank_cor',

        'desc',

        'created_at',
        'updated_at',
    ];
}
