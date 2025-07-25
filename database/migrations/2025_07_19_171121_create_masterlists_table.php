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
        Schema::create('masterlists', function (Blueprint $table) {
              $table->id();
            $table->string('title');
            $table->string('doc_code');
            $table->string('unit');
            $table->string('type');
            $table->integer('seq');
            $table->string('org_name');
            $table->string('username');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masterlists');
    }
};
