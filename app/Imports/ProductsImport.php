<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductImage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip if SKU is empty
            if (empty($row['sku'])) {
                continue;
            }

            // Check if product with this SKU already exists
            $existingProduct = Product::where('sku', $row['sku'])->first();

            if ($existingProduct) {
                // Update existing product
                $existingProduct->update([
                    'name' => $row['name'] ?? $existingProduct->name,
                    'description' => $row['description'] ?? $existingProduct->description,
                    'price' => $row['price'] ?? $existingProduct->price,
                    'quantity' => $row['quantity'] ?? $existingProduct->quantity,
                    'category' => $row['category'] ?? $existingProduct->category,
                ]);
                
                $product = $existingProduct;
            } else {
                // Create new product
                $product = Product::create([
                    'name' => $row['name'] ?? 'Unknown Product',
                    'description' => $row['description'] ?? null,
                    'price' => $row['price'] ?? 0,
                    'quantity' => $row['quantity'] ?? 0,
                    'category' => $row['category'] ?? 'Uncategorized',
                    'sku' => $row['sku'] ?? $this->generateUniqueSku(),
                ]);
            }

            // Handle images if provided in import
            if (isset($row['images']) && !empty($row['images'])) {
                $this->processImages($product, $row['images']);
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.sku' => 'required|string|max:100',
            '*.price' => 'nullable|numeric|min:0',
            '*.quantity' => 'nullable|integer|min:0',
            '*.category' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.name.required' => 'Product name is required',
            '*.sku.required' => 'SKU is required',
            '*.price.numeric' => 'Price must be a number',
            '*.quantity.integer' => 'Quantity must be a whole number',
        ];
    }

    private function generateUniqueSku(): string
    {
        do {
            $sku = 'PROD-' . Str::random(8) . '-' . time();
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }

    private function processImages($product, $imagesData): void
    {
        // Clear existing images if you want to replace them
        // $product->images()->delete();

        $imagePaths = explode(',', $imagesData);
        $order = $product->images()->max('order') ?? 0;

        foreach ($imagePaths as $imagePath) {
            $trimmedPath = trim($imagePath);
            
            if (!empty($trimmedPath)) {
                // Check if image already exists for this product
                $exists = ProductImage::where('product_id', $product->id)
                    ->where('image_path', $trimmedPath)
                    ->exists();

                if (!$exists) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $trimmedPath,
                        'order' => ++$order
                    ]);
                }
            }
        }
    }
}