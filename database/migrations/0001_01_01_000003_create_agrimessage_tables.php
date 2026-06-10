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
        // 1. regions
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['kecamatan', 'desa']);
            $table->foreignId('parent_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->timestamps();
        });

        // Update users table to add foreign key for region_id
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('region_id')->references('id')->on('regions')->nullOnDelete();
        });

        // 2. farmers
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('name');
            $table->string('phone', 20)->unique();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 3. farmer_groups
        Schema::create('farmer_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('leader_id')->nullable()->constrained('farmers')->nullOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->timestamps();
        });

        // 4. farmer_group_members (pivot)
        Schema::create('farmer_group_members', function (Blueprint $table) {
            $table->foreignId('farmer_id')->constrained('farmers')->cascadeOnDelete();
            $table->foreignId('farmer_group_id')->constrained('farmer_groups')->cascadeOnDelete();
            $table->primary(['farmer_id', 'farmer_group_id']);
        });

        // 5. templates
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');
            $table->timestamps();
        });

        // 6. broadcasts
        Schema::create('broadcasts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('template_id')->nullable()->constrained('templates')->nullOnDelete();
            $table->text('content');
            $table->timestamp('scheduled_at')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'processing', 'completed', 'failed'])->default('draft');
            $table->json('target_segment')->nullable(); // e.g. {"type": "region", "id": 1}
            $table->timestamps();
        });

        // 7. broadcast_recipients
        Schema::create('broadcast_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_id')->constrained('broadcasts')->cascadeOnDelete();
            $table->foreignId('farmer_id')->constrained('farmers')->cascadeOnDelete();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamps();
        });

        // 8. message_logs
        Schema::create('message_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_id')->nullable()->constrained('broadcasts')->nullOnDelete();
            $table->foreignId('farmer_id')->nullable()->constrained('farmers')->nullOnDelete();
            $table->string('phone', 20);
            $table->text('content');
            $table->enum('status', ['queued', 'sent', 'delivered', 'read', 'failed'])->default('queued');
            $table->string('fonnte_id')->nullable(); // To track message status from webhook
            $table->timestamps();
        });

        // 9. incoming_chats
        Schema::create('incoming_chats', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20);
            $table->foreignId('farmer_id')->nullable()->constrained('farmers')->nullOnDelete();
            $table->text('last_message')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 10. chat_replies
        Schema::create('chat_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incoming_chat_id')->constrained('incoming_chats')->cascadeOnDelete();
            $table->enum('sender_type', ['farmer', 'user']);
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_replies');
        Schema::dropIfExists('incoming_chats');
        Schema::dropIfExists('message_logs');
        Schema::dropIfExists('broadcast_recipients');
        Schema::dropIfExists('broadcasts');
        Schema::dropIfExists('templates');
        Schema::dropIfExists('farmer_group_members');
        Schema::dropIfExists('farmer_groups');
        Schema::dropIfExists('farmers');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
        });
        
        Schema::dropIfExists('regions');
    }
};
