<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->enum('kind', ['direct','group'])->default('direct');
            $table->string('title',120)->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->index(['kind','created_at']);
        });
        Schema::create('conversation_members', function (Blueprint $table) {
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role',['owner','admin','member'])->default('member');
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();
            $table->primary(['conversation_id','user_id']);
            $table->index(['user_id','last_read_at']);
        });
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->enum('type',['text'])->default('text');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index(['conversation_id','id']);
            $table->index(['sender_id','sent_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversation_members');
        Schema::dropIfExists('conversations');
    }
};
