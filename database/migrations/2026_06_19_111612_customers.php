<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
 
        Schema::create('customers' , function(Blueprint $table){

            $table->id();
            $table->string('name' , 250);
            $table->string('gender' , 150);
            $table->json('payment');
            $table->string('country' , 250);
            $table->string('image' , 255);

            $table->timestamps() ;


        });
     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
