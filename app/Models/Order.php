<?php

namespace App\Models;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'product_id',
        'quantity',
        'total_amount',
        'shipping_fee',
        'payment_method',
        'ship_method',
        'ship_address_id',
        'sender_name', // Thông tin người gửi
        'status',
        'voucher_id',
        'discount_value'
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_detail_id');
    }

    public function shipAddress()
    {
        return $this->belongsTo(Ship_address::class, 'ship_address_id', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany(Order_detail::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    public function getShippingAddressInfo()
    {
        if (!$this->shipAddress) {
            return [
                'recipient_name' => 'Chưa cập nhật',
                'phone_number' => 'Chưa cập nhật',
                'ship_address' => 'Địa chỉ chưa cập nhật',
                'full_address' => 'Thông tin địa chỉ không đầy đủ',
            ];
        }

        return [
            'recipient_name' => $this->shipAddress->recipient_name ?? 'Chưa cập nhật',
            'phone_number' => $this->shipAddress->phone_number ?? 'Chưa cập nhật',
            'ship_address' => $this->shipAddress->ship_address ?? 'Địa chỉ chưa cập nhật',
            'full_address' => trim($this->shipAddress->ship_address ?? 'Thông tin địa chỉ không đầy đủ')
        ];
    }

    public function hasCompleteShippingAddress()
    {
        return $this->shipAddress &&
                !empty($this->shipAddress->recipient_name) &&
                !empty($this->shipAddress->phone_number) &&
                !empty($this->shipAddress->ship_address);
    }

}