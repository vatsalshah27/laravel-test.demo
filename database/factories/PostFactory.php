<?php

namespace Database\Factories;
 
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    //protected $model = Post::class;

    public function definition()
    {
        $userId = 1;
        $userIdPost = 1;

        while ($userId === $userIdPost) {
            $userId = User::all()->random()->id;
            $userIdPost = User::all()->random()->id;
        }

        return [
            'title' => $this->faker->sentence, //Generates a fake sentence
            'slug' => $this->faker->sentence, //Generates a fake sentence
            'description' => $this->faker->paragraph(30), //generates fake 30 paragraphs
            'is_published' => 1,
            'user_id' => $userIdPost //Generates user id
        ];
    }
}
