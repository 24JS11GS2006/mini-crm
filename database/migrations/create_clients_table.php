<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nombre del cliente
            $table->string('document_number')->unique(); // número de documento (único)
            $table->string('email')->nullable(); // opcional
            $table->string('phone')->nullable(); // opcional
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
};