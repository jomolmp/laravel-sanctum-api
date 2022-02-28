<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    protected $table = 'tasks';
    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function toArray()
    {
        return[
            'name'=>$this->getAttribute('name'),
            'description'=>$this->getAttribute('description'),
            'status'=>$this->getAttribute('status'),
            'user'=>$this->getRelationValue('user')
        ];
    }
}
