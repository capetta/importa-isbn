<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->bigIncrements('id');
            $table->string('provider', 191);
            $table->string('provider_id', 191)->nullable();
            $table->text('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('authors')->nullable();
            $table->text('publisher')->nullable();
            $table->text('publishedDate')->nullable();
            $table->text('description')->nullable();
            $table->string('isbn10', 20)->nullable();
            $table->string('isbn13', 20)->nullable();
            $table->string('pageCount')->nullable();
            $table->string('height')->nullable();
            $table->string('width')->nullable();
            $table->string('length')->nullable();
            $table->string('printType')->nullable();
            $table->string('language')->nullable();
            $table->string('country')->nullable();
            $table->string('cover')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'isbn10', 'isbn13']);
            $table->unique(['provider', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
