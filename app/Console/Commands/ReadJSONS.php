<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use App\Category;
use App\Product;
use App\CategoriesAndProducts as CatAndProd;

class ReadJSONS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'read:jsons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads two JSON files and adds/updates entries to the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->comment('read jsons');
        $data = $this->read();
        $this->comment('processing...');
        $result = $this->updateOrCreate($data);
        $this->comment($result);
    }

    private function read()
    {
        return [
            'categories' => json_decode(file_get_contents(asset('categories.json')), true),
            'products' => json_decode(file_get_contents(asset('products.json')), true)
        ];
    }

    private function updateOrCreate(array $data)
    {
        $u = 0; //updated
        $c = 0; //created
        $e = 0; //errors

        foreach ($data['categories'] as $category) {
            $validator = Validator::make($category, [
                'external_id' => 'required|integer',
                'name' => 'required|string|max:200'
            ]);

            if (!$validator->fails()) {
                if (Category::where('external_id', $category['external_id'])->count()) {
                    $query = Category::where('external_id', $category['external_id'])->first();

                    if ($query->name != $category['name']) {
                        Category::where('external_id', $category['external_id'])->update(['name' => $category['name']]);
                        $u++;
                    }
                } else {
                    $model = new Category;
                    $model->external_id = $category['external_id'];
                    $model->name = $category['name'];
                    $model->save();
                    $c++;
                }
            } else {
                $e++;
            }
        }

        foreach ($data['products'] as $product) {
            $validator = Validator::make($product, [
                'external_id' => 'required|integer',
                'name' => 'required|string|max:200',
                'description' => 'max:1000',
                'price' => 'required',
                'category_id' => 'required',
                'quantity' => 'required|integer'
            ]);

            if (!$validator->fails()) {
                if (Product::where('external_id', $product['external_id'])->count()) {
                    $query = Product::where('external_id', $product['external_id'])->first();

                    if ($query->name != $product['name'] OR $query->price != $product['price'] OR $query->quantity != $product['quantity']) {
                        Product::where('external_id', $product['external_id'])->update(['name' => $product['name'], 'price' => $product['price'], 'quantity' => $product['quantity']]);

                        foreach ($product['category_id'] as $category_id) {
                            $category_ids = CatAndProd::where('external_id', $product['external_id'])->pluck();
                        }

                        $u++;
                    }
                } else {
                    $model = new Product;
                    $model->external_id = $product['external_id'];
                    $model->name = $product['name'];
                    $model->price = $product['price'];
                    $model->quantity = $product['quantity'];
                    $model->save();

                    foreach ($product['category_id'] as $category_id) {
                        $model = new CatAndProd;
                        $model->category_id = $category_id;
                        $model->product_id = $model->id;
                        $model->save();
                    }
                    $c++;
                }
            } else {
                $e++;
            }
        }

        return "Updated: $u, Created: $c, Errors: $e";
    }
}
