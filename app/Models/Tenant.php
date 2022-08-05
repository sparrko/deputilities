<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \LaravelArchivable\Archivable;

class Tenant extends Model
{
    use HasFactory;
    use Archivable;

    protected $table = 'tenants';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'surname',
        'name',
        'patr',
        'dateborn',
        'phone',
        'idUser',
        'idHouse',
        'room',
        'created_at',
        'updated_at',
        'archived_at',
        'icon'
    ];

    protected $casts = [
        'created_at' => 'date:d.m.Y',
        'updated_at' => 'datetime:d.m.Y',
        'archived_at' => 'datetime:d.m.Y',
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

    public function getAddressAttribute() {
        $tenant = $this;
        $house = House::withArchived()->find($tenant->idHouse);
        $street = Street::withArchived()->find($house->idStreet);

        $ret = "ул ";
        $ret .= $street->name;
        $ret .= " " . $house->number;
        $ret .= ", " . $tenant->room;

        return $ret;
    }

    public function house() {
        return $this->belongsTo('App\Models\House', 'idHouse', 'id');
    }
}
