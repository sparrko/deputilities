<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \LaravelArchivable\Archivable;
use Illuminate\Support\Facades\DB;

class House extends Model
{
    use HasFactory;
    use Archivable;

    protected $table = 'houses';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'number',
        'idStreet',
        'created_at',
        'updated_at',
        'archived_at',
    ];

    public function street() {
        return $this->belongsTo('App\Models\Street', 'idStreet', 'id');
    }

    public function getAllOptions() {
        return DB::select("SELECT `houses`.`id`, CONCAT(`streets`.`name`, ' ', `houses`.`number`) as text from `streets`, `houses` WHERE `houses`.`idStreet` = `streets`.`id`", array(1));
    }
}
