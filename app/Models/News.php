<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \LaravelArchivable\Archivable;

class News extends Model
{
    use HasFactory;
    use Archivable;

    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'title',
        'src',
        'type',
        'icon',
        'created_at',
        'updated_at',
        'archived_at',
    ];

    public const TYPE = [
        'staff' => 'staff',
        'tenants' => 'tenants'
    ];

    public function getMiniSrc() {
        $text = strip_tags($this->src, '<h1><h2><h3><h4><h5><p><a><td><tr><tbody><table><col><colgroup><img>');

        $text = mb_substr($text, 0, 1000);
        $text = str_replace("&ndash;", " ", $text);
        $text = str_replace("&laquo;", " ", $text);
        $text = str_replace("&raquo;", " ", $text);
        $text = str_replace("&nbsp;", " ", $text);

        return strip_tags($text) . "...";
    }
}
