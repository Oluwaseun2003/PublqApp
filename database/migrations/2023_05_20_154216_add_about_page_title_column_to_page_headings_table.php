<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_headings', function (Blueprint $table) {
            $table->string('about_page_title')->nullable()->after('contact_page_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_headings', function (Blueprint $table) {
            $table->dropColumn('about_page_title');
        });
    }
};
