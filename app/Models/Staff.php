<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \LaravelArchivable\Archivable;

class Staff extends Model
{
    use HasFactory;
    use Archivable;

    protected $table = 'staff';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'surname',
        'name',
        'patr',
        'phone',
        'role',
        'dateborn',
        'idUser',
        'created_at',
        'updated_at',
        'archived_at',
        'icon'
    ];

    public const ROLE = [
        'admin' => 'admin',
        'staffOfficer' => 'staffOfficer',
        'referenceOfficer' => 'referenceOfficer',
        'executor' => 'executor',
        'dispatcher' => 'dispatcher'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'idUser', 'id');
    }

    public function getSurnameNameAttribute() {
        return $this->surname . " " . $this->name;
    }
    public function getFullNameAttribute() {
        return $this->surname . " " . $this->name . " " . $this->patr;
    }
    public function getSubFullNameAttribute() {
        return $this->surname . " " . mb_substr($this->name, 0, 1) . ". " . mb_substr($this->patr, 0, 1) . ". ";
    }
}
