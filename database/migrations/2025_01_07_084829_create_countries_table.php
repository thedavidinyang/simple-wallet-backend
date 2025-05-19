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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->charset('utf8mb4');
            $table->char('iso3', 3)->charset('utf8mb4')->nullable();
            $table->char('numeric_code', 3)->charset('utf8mb4')->nullable();
            $table->char('iso2', 2)->charset('utf8mb4')->nullable();
            $table->string('phonecode', 255)->charset('utf8mb4')->nullable();
            $table->string('capital', 255)->charset('utf8mb4')->nullable();
            $table->string('currency', 255)->charset('utf8mb4')->nullable();
            $table->string('currency_name', 255)->charset('utf8mb4')->nullable();
            $table->string('currency_symbol', 255)->charset('utf8mb4')->nullable();
            $table->string('tld', 255)->charset('utf8mb4')->nullable();
            $table->string('native', 255)->charset('utf8mb4')->nullable();
            $table->string('region', 255)->charset('utf8mb4')->nullable();
            $table->unsignedBigInteger('region_id')->unsigned()->nullable();
            $table->string('subregion', 255)->charset('utf8mb4')->nullable();
            $table->unsignedBigInteger('subregion_id')->unsigned()->nullable();
            $table->string('nationality', 255)->charset('utf8mb4')->nullable();
            $table->text('timezones')->charset('utf8mb4')->nullable();
            $table->text('translations')->charset('utf8mb4')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('emoji', 191)->charset('utf8mb4')->nullable();
            $table->string('emojiU', 191)->charset('utf8mb4')->nullable();
            $table->timestamps();
            $table->boolean('flag')->default(true);
            $table->string('wikiDataId', 255)->charset('utf8mb4')->nullable()->comment('Rapid API GeoDB Cities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
