<?php

namespace Database\Factories;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class SliderFactory extends Factory
{
    protected $model=Slider::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['title' => "string", 'url' => "string", 'img' => "string", 'status' => "int", 'seq' => "int"])]
    public function definition(): array
    {
        return [
            'title' => $this->faker->text(20),
            'url' => '',
            'img'=>'https://i.postimg.cc/05C5HS7N/bg.jpg',
            'status' => 1,
            'seq' => $this->faker->numberBetween(1,999)
        ];
    }
}
