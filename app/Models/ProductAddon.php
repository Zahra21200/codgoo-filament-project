<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAddons extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'product_addons';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }

}
