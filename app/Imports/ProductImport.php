<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\VariantType;
use App\Models\VariantValue;
use App\Traits\InventoryTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductImport implements ToCollection, WithHeadingRow, WithStartRow
{
    use InventoryTrait;

    public function __construct()
    {
        $this->categoryId = null;
        $this->projectId = null;
        $this->productName = null;
    }

    public function startRow(): int
    {
        return 4;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
            $this->storeCategory($row);
            $this->storeProduct($row);
        }
    }

    public function storeCategory(Collection $row)
    {
        if (! empty($row['category'])) {
            $categoryName = $row['category'];
            $parentCategoryName = $row[2];
            $parentCategoryId = null;
            $isParent = 1;

            $category = Category::whereRaw('LOWER(category_name) = ?', [Str::lower($categoryName)])->first();
            if (strtolower($row[1]) == 'no') {
                $isParent = 0;
                if (! empty($parentCategoryName)) {
                    $parentCategory = Category::whereRaw('LOWER(category_name) = ?', [Str::lower($parentCategoryName)])->first();
                    if ($parentCategory == null) {
                        $parentCategory = Category::create(['category_name' => ucwords(strtolower($parentCategoryName)), 'is_parent' => 1]);
                    }
                    $parentCategoryId = $parentCategory->id ?? null;
                }
            }
            $newCategory = Category::updateOrCreate(
                ['id' => $category->id ?? null],
                [
                    'category_name' => ucwords(strtolower($categoryName)),
                    'is_parent' => $isParent,
                    'parent_category_id' => $parentCategoryId,
                    'banner_image' => $row[3] ? 'category/'.$row[3] : null,
                    'image' => $row[4] ? 'category/'.$row[4] : null,
                    'header_image' => $row[5] ? 'category/'.$row[5] : null,
                ]
            );
            $this->categoryId = $newCategory->id;
        }
    }

    public function storeProduct(Collection $row)
    {
        if (! empty($row['product'])) {
            $product = Product::whereRaw('product_code = ?', [trim($row[8])])->first();
            $newProduct = Product::updateOrCreate(
                ['id' => $product->id ?? null],
                [
                    'category_id' => $this->categoryId,
                    'product_name' => ucwords(strtolower(trim($row['product']))),
                    'product_code' => $row[8] ? trim($row[8]) : ucwords(strtolower(trim($row['product']))),
                    'image' => $row[16] ? 'product/'.trim($row[16]) : null,
                    'description' => ucwords(strtolower(trim($row[17]))),
                    'stock_status' => 1,
                    'gst' => $row[13] ? trim($row[13]) : 5,
                    'cgst' => $row[14] ? trim($row[14]) : 2.5,
                    'sgst' => $row[15] ? trim($row[15]) : 2.5,
                    'is_seasonal' => trim($row[7]) == 'NO' ? 0 : '1',
                    'is_perishable' => trim($row[18]) == 'NO' ? 0 : '1',
                    'perishable_location' => strtolower(trim($row[19])),
                    'shelf_life' => $row[20] ? trim(str_replace('DAYS', '', $row[20])) : null,
                ]
            );
            $this->projectId = $newProduct->id;
            $this->productName = $newProduct->product_name;
        }

        $this->storeProductVarient($row);
        $this->storeProductImage($row);
    }

    private function getVariantType($name)
    {
        $type = VariantType::where('status', 1)->where('name', $name)->select('name', 'id')->first();

        if (empty($type)) {
            $type = VariantType::create(['name' => $name]);
        }

        return $type->id;
    }

    private function getVariantValue($name)
    {
        $type = VariantValue::where('status', 1)->where('name', $name)->select('name', 'id')->first();

        if (empty($type)) {
            $type = VariantValue::create(['name' => $name]);
        }

        return $type->id;
    }

    public function storeProductVarient(Collection $row)
    {
        if (! empty($row['product_variant'])) {

            $variantType = $this->getVariantType($row['product_variant']);
            $variantValue = $this->getVariantValue($row[22]);
            $subName = $row[22].' '.$row['product_variant'];
            $variantQty = 1;

            // if(is_integer($variantQty)){
            $productVariant = ProductVariant::where('product_id', $this->projectId)
                ->whereRaw('variant_type = ?', [$variantType])->whereRaw('variant_value = ?', [$variantValue])
                                // ->whereRaw('qty_type = ?', [$variantQtyType])->whereRaw('qty = ?', [$variantQty])
                ->first();

            $product_variant = ProductVariant::updateOrCreate(
                ['id' => $productVariant->id ?? null],
                [
                    'category_id' => $this->categoryId,
                    'product_id' => $this->projectId,
                    'product_name' => $this->productName.' '.$subName,
                    'variant_type' => $variantType,
                    'variant_value' => $variantValue,
                    'qty' => $variantQty ?? 1,
                    'mrp' => $row[23] ? trim($row[23]) : 0,
                    'sp' => $row[23] ? trim($row[24]) : 0,
                ]
            );

            $this->stockLog($this->projectId, $product_variant->id, $product_variant->qty, 0, 'Add New Product');
            // }
        }
    }

    public function subStringExistsInArray(?string $substring, array $array)
    {
        foreach ($array as $key => $str) {
            if (str_contains($substring, $str)) {
                return $str; // Substring exists in this string
            }
        }

        return $array[0]; // Substring does not exist in any string
    }

    public function storeProductImage(Collection $row)
    {
        if (! empty($row['product_images'])) {
            ProductImage::create([
                'product_id' => $this->projectId,
                'image' => 'product/'.trim($row['product_images']),
                'sort_order' => trim($row[26]) ?? 0,
            ]);
        }
    }
}
