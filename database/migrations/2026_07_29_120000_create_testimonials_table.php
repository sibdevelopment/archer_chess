<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('ACTIVE');
            $table->string('name')->nullable();
            $table->string('designation')->nullable();
            $table->longText('review')->nullable();
            $table->string('rating')->default('5');
            $table->longText('image')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->string('card_class')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });

        DB::table('testimonials')->insert([
            [
                'status' => 'ACTIVE',
                'name' => 'John Smith',
                'designation' => 'Student Father',
                'review' => 'My Sanchi has been Learning Chess with ACA since she was 6 years and won Mumbai District Championship Under 6 Category. She has also won so many tournaments in his category and the interschool Chess Championship 2022, all thanks to ACA! Now She is preparing for Fide Rated Tournament, and ACA gives all details of the official tournament and guides from time to time.',
                'rating' => '5',
                'image' => '/frontend1/assets/images/thumbs/testimonial-img1.png',
                'display_order' => 1,
                'card_class' => 'bg-main-600',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status' => 'ACTIVE',
                'name' => 'William John',
                'designation' => 'Student Father',
                'review' => 'The instructors have been incredibly knowledgeable and skilled, and they have done an amazing job of conveying their expertise in a way that is easy to understand and follow. The lessons are well-planned and organized, and the materials provided are informative and helpful.',
                'rating' => '5',
                'image' => '/frontend1/assets/images/thumbs/testimonial-img2.png',
                'display_order' => 2,
                'card_class' => 'bg-pink-600',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status' => 'ACTIVE',
                'name' => 'Michel Smith',
                'designation' => 'Student Father',
                'review' => 'My son Raghul is only 6 years, and now he has won so many tournaments in his category and wants to become Grandmaster, all thanks to ACA! Regular classes, practice tournaments, and a structured curriculum is the opportunity to approach learning chess in the best way possible.',
                'rating' => '5',
                'image' => '/frontend1/assets/images/thumbs/testimonial-img3.png',
                'display_order' => 3,
                'card_class' => 'bg-main-two-600',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('testimonials');
    }
};
