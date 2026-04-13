<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities');
        });

        Schema::create('client_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->decimal('volume_m3', 10, 2)->nullable();
            $table->decimal('gross_weight_kg', 10, 2)->nullable();
            $table->text('comments')->nullable();
            $table->foreignId('origin_id')->nullable()->constrained('locations');
            $table->foreignId('destination_id')->nullable()->constrained('locations');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('estado')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('commercial_offers', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->foreignId('client_request_id')->nullable()->constrained('client_requests');
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('incoterm_id')->nullable()->constrained('incoterms');
            $table->foreignId('origin_port_id')->nullable()->constrained('ports');
            $table->foreignId('destination_port_id')->nullable()->constrained('ports');
            $table->foreignId('container_type_id')->nullable()->constrained('container_types');
            $table->decimal('price', 10, 2)->nullable();
            $table->date('valid_until')->nullable();
            $table->string('status')->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('odoo_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('logistics_operations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->foreignId('commercial_offer_id')->nullable()->constrained('commercial_offers');
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->string('status')->default('preparation');
            $table->date('etd')->nullable();
            $table->date('eta')->nullable();
            $table->date('atd')->nullable();
            $table->date('ata')->nullable();
            $table->unsignedBigInteger('odoo_id')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_type_id')->nullable();
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->integer('file_size_bytes')->nullable();
            $table->string('mime_type')->nullable();
            $table->boolean('is_encrypted')->default(false);
            $table->text('encryption_key')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('login_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable();
            $table->timestamp('logged_in_at')->nullable();
            $table->timestamp('logged_out_at')->nullable();
            $table->timestamp('token_expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_sessions');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('logistics_operations');
        Schema::dropIfExists('commercial_offers');
        Schema::dropIfExists('client_requests');
        Schema::dropIfExists('locations');
    }
};
