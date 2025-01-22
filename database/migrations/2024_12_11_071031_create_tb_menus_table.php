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
        Schema::create('tb_menus', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('url');
            $table->integer('parent_id')->nullable();
            $table->integer('order');
            $table->text('icon');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
        Artisan::call('db:seed', ['--class' => 'TbMenusSeeder']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_menus');
    }
};
