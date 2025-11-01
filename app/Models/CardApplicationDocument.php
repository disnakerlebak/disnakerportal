<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardApplicationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_application_id',
        'type',
        'file_path',
    ];

    public function application()
    {
        return $this->belongsTo(CardApplication::class, 'card_application_id');
    }
}
