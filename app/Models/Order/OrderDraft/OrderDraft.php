<?php

namespace App\Models\Order\OrderDraft;

use App\Helper\SettingHelper;
use App\Helper\StringHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDraft extends Model
{
    use HasFactory;

    protected $appends = ['order_total', 'currency'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order_draft_items()
    {
        return $this->hasMany(OrderDraftItem::class);
    }

    public function getOrderTotalAttribute()
    {
        $order_draft_items = $this->order_draft_items;
        $total = 0.0;

        foreach ($order_draft_items as $order_draft_item) {
            if ($order_draft_item->product->is_sale == 1) {
                if ($order_draft_item->variant_id) {
                    $total += StringHelper::discount($order_draft_item->variant->price, $order_draft_item->variant->discount);
                } else {
                    $total += StringHelper::discount($order_draft_item->product->price, $order_draft_item->product->discount);
                }
            } else {
                if ($order_draft_item->variant_id) {
                    $total += $order_draft_item->variant->price;
                } else {
                    $total += $order_draft_item->product->price;
                }
            }
        }

        return $total;
    }

    public function getCurrencyAttribute()
    {
        $currency = SettingHelper::currency_sign();
        return $currency;
    }
}
