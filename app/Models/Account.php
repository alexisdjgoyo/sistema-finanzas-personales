<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'type',
        'balance',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'balance'    => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the account.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the transactions for the Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getCurrentBalanceAttribute(): float
    {
        $balance = $this->balance ?? 0;

        $totalTransactions = $this->transactions()
            ->selectRaw(
                'SUM(
                CASE
                    WHEN categories.type = ? THEN transactions.amount
                    WHEN categories.type = ? THEN -transactions.amount
                    WHEN categories.type = ? AND transactions.description like "%Salida de Transferencia%" THEN -transactions.amount
                    WHEN categories.type = ? AND transactions.description like "%Entrada de Transferencia%" THEN transactions.amount
                    ELSE 0
                END
            ) as total',
                [
                    CategoryType::INCOME->value,
                    CategoryType::SPENDING->value,
                    CategoryType::TRANSFER->value,
                    CategoryType::TRANSFER->value
                ]
            )
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->value('total');

        return $balance + ($totalTransactions ?? 0);
    }
}
