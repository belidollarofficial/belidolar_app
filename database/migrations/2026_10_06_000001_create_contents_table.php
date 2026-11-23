<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->string('key');
            $table->string('label');
            $table->string('type')->default('text');
            $table->text('value')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['section', 'key']);
            $table->index('section');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
