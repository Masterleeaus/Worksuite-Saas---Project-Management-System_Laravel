
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('ai_tools_messages', function(Blueprint $t){
            $t->id();
            $t->unsignedBigInteger('conversation_id');
            $t->string('role');
            $t->longText('content');
            $t->json('meta')->nullable();
            $t->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('ai_tools_messages'); }
};
