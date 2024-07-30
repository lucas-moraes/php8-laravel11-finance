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
        Schema::create('lc_movimento', function (Blueprint $table) {
            $table->increments('id')->nullable();
            $table->integer('dia')->nullable();
            $table->integer('mes')->nullable();
            $table->integer('ano')->nullable();
            $table->string('tipo')->nullable();
            $table->integer('categoria')->nullable();
            $table->string('descricao')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lc_movimento');
    }
};
