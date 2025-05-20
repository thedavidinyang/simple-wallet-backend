<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $fillable = [
        'user_id',
        'amount',
        'type',
        'status',
        'transaction_id',
        'transaction_reference',
    ];
    public $casts = [
        'amount' => 'decimal:2',
    ];
    public $hidden = [
        'updated_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
    public function scopeCredit($query)
    {
        return $query->where('type', 'credit');
    }
    public function scopeDebit($query)
    {
        return $query->where('type', 'debit');
    }
    public function scopeWithTransactionId($query, $transactionId)
    {
        return $query->where('transaction_id', $transactionId);
    }
    public function scopeWithWalletId($query, $walletId)
    {
        return $query->where('wallet_id', $walletId);
    }
    public function scopeWithUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    public function scopeWithTransactionType($query, $type)
    {
        return $query->where('type', $type);
    }
    public function scopeWithDescription($query, $description)
    {
        return $query->where('description', 'like', '%' . $description . '%');
    }
    public function scopeWithAmount($query, $amount)
    {
        return $query->where('amount', $amount);
    }
    public function scopeWithDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
    public function scopeWithCreatedAt($query, $createdAt)
    {
        return $query->where('created_at', $createdAt);
    }
    public function scopeWithUpdatedAt($query, $updatedAt)
    {
        return $query->where('updated_at', $updatedAt);
    }
    public function scopeWithTransactionIdAndWalletId($query, $transactionId, $walletId)
    {
        return $query->where('transaction_id', $transactionId)->where('wallet_id', $walletId);
    }
    public function scopeWithTransactionIdAndUserId($query, $transactionId, $userId)
    {
        return $query->where('transaction_id', $transactionId)->where('user_id', $userId);
    }
    public function scopeWithWalletIdAndUserId($query, $walletId, $userId)
    {
        return $query->where('wallet_id', $walletId)->where('user_id', $userId);
    }
    public function scopeWithTransactionIdAndType($query, $transactionId, $type)
    {
        return $query->where('transaction_id', $transactionId)->where('type', $type);
    }
    public function scopeWithWalletIdAndType($query, $walletId, $type)
    {
        return $query->where('wallet_id', $walletId)->where('type', $type);
    }
}
