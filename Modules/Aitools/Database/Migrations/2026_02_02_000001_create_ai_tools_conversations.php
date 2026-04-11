
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        if (Schema::hasTable('ai_tools_conversations')) {
            return;
        }
        Schema::create('ai_tools_conversations', function(Blueprint $t){
            $t->id();
            $t->unsignedBigInteger('company_id')->nullable();
            $t->unsignedBigInteger('user_id')->nullable();
            $t->string('title')->nullable();
            $t->string('status')->default('open');
            $t->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('ai_tools_conversations'); }
};
