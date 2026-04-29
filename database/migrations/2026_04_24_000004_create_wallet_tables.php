<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->uuid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('wallet_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('wallet_id')->constrained()->onDelete('cascade');
            $table->string('currency')->default('GHS');
            $table->decimal('balance', 16, 8)->default(0);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('wallet_id')->constrained()->onDelete('cascade');
            $table->string('reference')->unique();
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 16, 8);
            $table->string('currency')->default('GHS');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            $table->string('description')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('wallet_balances');
        Schema::dropIfExists('wallets');
    }
};
