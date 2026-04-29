<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->uuid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            
            $table->string('number')->nullable();
            $table->enum('status', [
                'pending', 
                'processing', 
                'activating', 
                'waiting_sms', 
                'completed', 
                'expired', 
                'cancelled', 
                'refunded'
            ])->default('pending');
            
            $table->decimal('cost', 16, 8);
            $table->decimal('price', 16, 8);
            $table->string('currency')->default('GHS');
            $table->string('external_id')->nullable();
            $table->text('sms_text')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
