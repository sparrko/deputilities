<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \LaravelArchivable\Archivable;

class TicketType extends Model
{
    use HasFactory;
    use Archivable;

    protected $table = 'ticketTypes';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'archived_at',
    ];

    public function tickets() {
        return $this->hasMany('App\Models\Ticket', 'idTicketType', 'id');
    }
}
