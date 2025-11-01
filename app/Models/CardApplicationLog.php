<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardApplicationLog extends Model
{
    protected $fillable = [
      'card_application_id','actor_id','action','from_status','to_status',
      'notes','ip','user_agent'
    ];

    public function application(){ return $this->belongsTo(CardApplication::class,'card_application_id'); }
    public function actor(){ return $this->belongsTo(User::class,'actor_id'); }
}
