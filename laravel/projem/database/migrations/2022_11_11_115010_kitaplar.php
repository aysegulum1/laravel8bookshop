<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Kitaplar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kitaplar', function (Blueprint $table) {
            $table->id('id');
            $table->string('kategori');
            $table->string('kitapadi');
            $table->decimal('fiyat');
            $table->string('slug')->unique();
            $table->boolean('is_active');
       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kitaplar');
    }
}
