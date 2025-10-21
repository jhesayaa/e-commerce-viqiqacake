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
        Schema::table('produks', function (Blueprint $table) {
            $table->softDeletes();
        });
    
        Schema::table('kategoris', function (Blueprint $table) {
            $table->softDeletes();
        });
    
        Schema::table('pesanan_items', function (Blueprint $table) {
            $table->softDeletes();
        });
    
        Schema::table('pesanans', function (Blueprint $table) {
            $table->softDeletes();
        });
    
        Schema::table('vouchers', function (Blueprint $table) {
            $table->softDeletes(); 
        });
    }
    
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    
        Schema::table('pesanan_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
