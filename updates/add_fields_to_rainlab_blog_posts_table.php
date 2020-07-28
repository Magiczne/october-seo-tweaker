<?php namespace Magiczne\SeoTweaker\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use October\Rain\Support\Facades\Schema;
use System\Classes\PluginManager;

class AddFieldsToRainLabBlogPostsTable extends Migration
{
    public function up()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Blog'))
        {
            Schema::table('rainlab_blog_posts', function ($table) {
                $table->string('seo_keywords')->nullable();
                $table->string('seo_canonical_url')->nullable();
                $table->string('seo_redirect_url')->nullable();
                $table->string('seo_robots_index')->nullable();
                $table->string('seo_robots_follow')->nullable();
            });
        }
    }

    public function down()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Blog'))
        {
            Schema::table('rainlab_blog_posts', function ($table) {
                $table->dropColumn('seo_keywords');
                $table->dropColumn('seo_canonical_url');
                $table->dropColumn('seo_redirect_url');
                $table->dropColumn('seo_robots_index');
                $table->dropColumn('seo_robots_follow');
            });
        }
    }
}
