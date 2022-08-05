<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \LaravelArchivable\Archivable;

class Street extends Model
{
    use HasFactory;
    use Archivable;

    protected $table = 'streets';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'archived_at',
    ];

    public function houses() {
        return $this->hasMany('App\Models\House', 'idStreet', 'id');
    }
}
