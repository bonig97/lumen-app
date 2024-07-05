<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('profile_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->string('attribute');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_attributes');
    }
}
