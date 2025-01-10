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
        Schema::create('tb_about_news_type', function (Blueprint $table) {
            $table->id();
            $table->string('about_news_name_en');
            $table->string('about_news_name_km');
            $table->integer('news_type_id');
            $table->integer('order');
            $table->boolean('isUsed')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_about_news_type');
    }
};
