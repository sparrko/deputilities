<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \LaravelArchivable\Archivable;

class Ticket extends Model
{
    use HasFactory;
    use Archivable;

    protected $table = 'tickets';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'idTenant',
        // 'idHouse',
        // 'room',
        'idTicketType',
        'desc',
        'archiveDesc',
        'idDispatcher',
        'idExecutor',
        'idUserArchive',
        'created_at',
        'updated_at',
        'completed_at',
        'archived_at'
    ];

    public const STATUS = [
        'new' => 'new',
        'in_work' => 'in_work',
        'completed' => 'completed',
        'archived' => 'archived',
    ];

    public function status() {
        if ($this->completed_at == null &&
            $this->archived_at == null &&
            $this->idExecutor == null) {
            return 'new';
        }
        else if ($this->completed_at == null &&
                 $this->archived_at == null &&
                 $this->idExecutor != null) {
            return 'in_work';
        }
        else if ($this->archived_at != null) {
            return 'archived';
        } else if ($this->completed_at != null) {
            return 'completed';
        }
    }

    public function iconStatus() {
        $status = $this->status();
        if ($status == 'new') { return asset('images/ticket_status/new.png'); }
        else if ($status == 'in_work') { return asset('images/ticket_status/in_work.gif'); }
        else if ($status == 'archived') { return asset('images/ticket_status/canceled.png'); }
        else if ($status == 'completed') { return asset('images/ticket_status/completed.png'); }
    }

    public function tenant() {
        return $this->belongsTo('App\Models\Tenant', 'idTenant', 'id');
    }

    // public function house() {
    //     return $this->belongsTo('App\Models\House', 'idHouse', 'id');
    // }

    public function type() {
        return $this->belongsTo('App\Models\TicketType', 'idTicketType', 'id');
    }

    public function dispatcher() {
        return $this->belongsTo('App\Models\Staff', 'idDispatcher', 'id');
    }

    public function executor() {
        return $this->belongsTo('App\Models\Staff', 'idExecutor', 'id');
    }

    public function userArchive() {
        return $this->belongsTo('App\Models\User', 'idUserArchive', 'id');
    }
}
