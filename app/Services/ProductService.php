<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductVariantPrice;
use App\Repositories\ProductRepository;
use App\Traits\FileTrait;
use App\Traits\InventoryTrait;

class ProductService
{
    use FileTrait, InventoryTrait;

    protected $repository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->repository = $productRepository;
    }

    public function apiList($categoryId)
    {
        $products = $this->repository->getQueryBuilder(null, 'sort_order', 'asc')
            ->select('id', 'name', 'image', 'category_id')
            // ->with('product_variant:id,product_id,product_name,mrp,sp')
            ->with([
                'product_variant' => function ($query) {
                    //$query->where('qty', '>', 0);
                    $query->with(['variantType:id,name', 'variantValue:id,name']);
                },
            ])
            ->where('status', config('constants.ACTIVE'))
            ->when($categoryId !== null, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->whereHas('product_variant', function ($query) {
                //$query->where('qty', '>', 0);
            })
            ->get();

        return ProductResource::collection($products);
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'name') ?? 'name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $products = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->select('id', 'category_type_id', 'category_id', 'name', 'sort_order', 'status', 'is_parent', 'code', 'image')
            ->with('category:id,name', 'categoryType:id,name', 'variantPrices:id,product_id,zone_id,price')
            ->get();
        $productArray = [];

        if ($products) {
            foreach ($products as $key => $product) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="' . $product->id . '" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['category_name'] = $product->categoryType->name ?? '';
                $data['category_type'] = $product->name ?? "";
                $data['product_name'] = $product->name ?? "";
                $data['product_code'] = $product->code ?? "";
                $data['document'] = $product->image !== null ? '<a href="' . asset('storage/' . $product->image) . '" target="_blank" >Document</a>' : null;
                $data['gst'] = $product->gst ?? "-";
                $data['cgst'] = $product->cgst ?? "-";
                $data['sgst'] = $product->sgst ?? "-";
                $east = $product->variantPrices->where('zone_id', 1)->first()->price ?? "-";
                $west = $product->variantPrices->where('zone_id', 2)->first()->price ?? "-";
                $north = $product->variantPrices->where('zone_id', 3)->first()->price ?? "-";
                $south = $product->variantPrices->where('zone_id', 4)->first()->price ?? "-";
                $data['east_mrp'] = $east;
                $data['north_mrp'] = $north;
                $data['south_mrp'] = $south;
                $data['west_mrp'] = $west;
                $data['is_parent'] = $product->is_parent === 1 ? 'Yes' : 'No';
                $data['sort_order'] = $product->sort_order;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='" . $product->id . "' " . ($product->status == config('constants.ACTIVE') ? 'checked' : '') . '/></div>';
                $data['action'] = "<a href='" . route('product.edit', $product->id) . "' title='Edit'><i class='fa fa-edit'></i></a>";
                $productArray[] = $data;
            }
        }

        return $productArray;
    }

    public function getLastSortId()
    {
        return $this->repository->getLastSortId('sort_order');
    }

    private function seoKeyword($name, $keyword)
    {
        if (empty($keyword)) {
            $find = [' ', '`', '`', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '/', '|', '<', '>', '.', '?', '[', ']', '{', '}', '=', "'", '"', ','];
            $replace = ['-', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

            $keyword = str_replace($find, $replace, strtolower($name));
        }

        return $keyword;
    }

    public function create(array $requestData)
    {
        // $requestData['seo_keyword'] = $this->seoKeyword($requestData['name'], $requestData['seo_keyword']);
        $requestData['image'] = $this->getFileImage($requestData['image']);
        $requestData['stock_status'] = $requestData['stock_status'] ?? 0;
        $requestData['is_fast_selling'] = $requestData['is_fast_selling'] ?? 0;
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        $product = $this->repository->updateOrCreate($requestData, null);
        $this->variantUpdateOrCreate($requestData['variant'] ?? [], $product);
        $this->multiImage($requestData['multiImage'] ?? [], $product);

        return $product;
    }

    private function variantUpdateOrCreate(array $variantData, Product $product)
    {
        $variantQty = $product->product_variant->pluck('qty', 'id');
        $ids = $zonePriceIds = [];

        // echo '<pre>'; print_r($variantData); die;
        if (count($variantData) > 0) {
            foreach ($variantData as $key => $variant) {
                if (!empty($variant)) {
                    $id = $product->product_variant()->updateOrCreate(
                        ['id' => $variant['id'] ?? null],
                        [
                            'category_id' => $product->category_id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'variant_type' => null, // $variant['variant_type'],
                            'variant_value' => null, // $variant['variant_value'],
                            'qty' => 1, // $variant['case_quantity'], //$variant['qty'],
                            // 'mrp' => $variant['mrp'],
                            // 'sp' => $variant['sp'],
                        ]
                    )->id;

                    $ids[] = $id;

                    // echo '<pre>'; print_r($variant);
                    if (count($variant['mrp']) > 0) {
                        foreach ($variant['mrp'] as $zoneId => $zonePrice) {
                            // print_r($zoneId); print_r($zonePrice);
                            $priceId = ProductVariantPrice::updateOrCreate(
                                [
                                    'product_id' => $product->id,
                                    'variant_id' => $id,
                                    'zone_id' => $zoneId,
                                ],
                                [
                                    'product_id' => $product->id,
                                    'variant_id' => $id,
                                    'zone_id' => $zoneId,
                                    'price' => $zonePrice,
                                    'case_quantity' => 1, // $variant['case_quantity'],
                                ]
                            )->id;

                            $zonePriceIds[] = $priceId;
                        }
                    }

                    /*$variantOldQty = (isset($variant['id']) && $variant['id'] !== null) ? $variantQty[$variant['id']] : 0;
                    $title = (isset($variant['id']) && $variant['id'] !== null) ? 'Update Product' : 'Add New Product';

                    // Variant Log stock change
                    $this->stockLog($product->id, $id, ($variant['qty'] - $variantOldQty), $variantOldQty, $title);*/
                }
            }
        }
        // dd($ids);
        $product->product_variant()->whereNotIn('id', $ids)->delete();

        $product->variantPrices()->whereNotIn('id', $zonePriceIds)->delete();

        return $ids;
    }

    private function multiImage(array $multiImages, Product $product)
    {
        $ids = [];
        // echo '<pre>'; print_r($multiImages); die;
        if (count($multiImages) > 0) {
            foreach ($multiImages as $key => $image) {
                $ids[] = $product->productimage()->updateOrCreate(
                    ['id' => $image['id'] ?? null],
                    [
                        'product_id' => $product->id,
                        'image' => $this->getFileImage($image['image'], $image['edit_image'] ?? null),
                        'sort_order' => $image['sort_order'],
                    ]
                )->id;
            }
        }
        // dd($ids);
        $product->productimage()->whereNotIn('id', $ids)->delete();

        return $ids;
    }

    private function overviewImage(array $images)
    {
        if (!empty($images)) {
            foreach ($images as $key => $image) {
                $overviewImage[] = [
                    'image' => $this->getFileImage($image['image'], $image['edit_image']),
                    'sort_order' => $image['sort_order'],
                ];
            }

            return json_encode($overviewImage);
        }

        return null;
    }

    public function update(array $requestData, Product $product)
    {
        // dd($requestData);
        // $requestData['seo_keyword'] = $this->seoKeyword($requestData['name'], $requestData['seo_keyword']);
        $requestData['image'] = $this->getFileImage($requestData['image'], $requestData['edit_image']);
        $requestData['stock_status'] = $requestData['stock_status'] ?? 0;
        $requestData['is_fast_selling'] = $requestData['is_fast_selling'] ?? 0;
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        $product = $this->repository->updateOrCreate($requestData, $product);
        $this->variantUpdateOrCreate($requestData['variant'] ?? [], $product);
        $this->multiImage($requestData['multiImage'] ?? [], $product);

        return $product;
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDelete($requestData);
    }

    public function bulkUpdate(string $columnName, array $requestData)
    {
        $status = false;
        $message = __('message.oopsError');

        if ($this->repository->bulkUpdate($columnName, $requestData)) {
            $status = true;
            $message = __('message.statusSuccessUpdate');
        }

        return ['status' => $status, 'message' => $message];
    }
}
