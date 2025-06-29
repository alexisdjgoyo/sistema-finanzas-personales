<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'allocated_amount',
        'start_date',
        'end_date',
    ];

    /**
     * Get the user that owns the budget.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category for the budget.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
