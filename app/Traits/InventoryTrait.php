<?php

namespace App\Traits;

use App\Models\ProductStockLog;

trait InventoryTrait
{
    private function stockLog($productId, $productVariantId, $quantity, $oldQuantity, $description)
    {
        if (abs($quantity) > 0) {
            ProductStockLog::create([
                'product_id' => $productId,
                'product_variant_id' => $productVariantId,
                'quantity' => $quantity,
                'old_quantity' => $oldQuantity,
                'description' => $description,
            ]);
        }
    }
}
