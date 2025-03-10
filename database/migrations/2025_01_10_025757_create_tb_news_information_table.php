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
        Schema::create('tb_news_information', function (Blueprint $table) {
            $table->id();
            $table->integer('news_type_id');
            $table->string('title_en');
            $table->string('title_km');
            $table->text('image');
            $table->text('url_video')->nullable();
            $table->text('location')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('tb_news_information');
    }
};
