<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Good;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class GoodFactory extends Factory
{
    //设置对于模型
    protected $model=Good::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition(): array
    {

        return [
            'user_id' => 1,
            'title'=>$this->faker->text(20),
            'category_id'=>$this->faker->randomElement(Category::where(['level' => 3,'group' => 'goods'])->pluck('id')),
            'description'=>$this->faker->text(40 ),
            'price'=>$this->faker->numberBetween(1,100000),
            'stock'=>$this->faker->numberBetween(1,10000),
            'cover'=>'https://i.postimg.cc/05C5HS7N/bg.jpg',
            'pics'=>[
                'https://i.postimg.cc/05C5HS7N/bg.jpg',
                'https://i.postimg.cc/05C5HS7N/bg.jpg',
                'https://i.postimg.cc/05C5HS7N/bg.jpg',
            ],
            'is_on' => $this->faker->randomElement([0,1]),
            'is_recommend' => $this->faker->randomElement([0,1]),
            'details'=>$this->faker->paragraphs(4,true),
        ];
    }
}
