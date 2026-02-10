<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('equipped_badges')->nullable()->after('bio');
        });

        // Migrate separate valid string badges to JSON array
        // We use raw PHP here to be safe across DB drivers or just simple loop
        // But for migration, simple SQL is best if supported. 
        // Let's use Eloquent or DB to be safe.
        $users = DB::table('users')->whereNotNull('equipped_badge')->get();
        foreach ($users as $u) {
            if (!empty($u->equipped_badge)) {
                DB::table('users')->where('id', $u->id)->update([
                    'equipped_badges' => json_encode([$u->equipped_badge])
                ]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('equipped_badge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('equipped_badge')->nullable();
        });

        // Restore first badge
        $users = DB::table('users')->whereNotNull('equipped_badges')->get();
        foreach ($users as $u) {
            $badges = json_decode($u->equipped_badges, true);
            if (is_array($badges) && count($badges) > 0) {
                DB::table('users')->where('id', $u->id)->update([
                    'equipped_badge' => $badges[0]
                ]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('equipped_badges');
        });
    }
};
