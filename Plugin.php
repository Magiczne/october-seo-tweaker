<?php namespace Magiczne\SeoTweaker;

use Backend\Widgets\Form;
use Cms\Classes\Page;
use Cms\Classes\Theme;
use Illuminate\Support\Facades\Event;
use October\Rain\Exception\ApplicationException;
use October\Rain\Support\Facades\Yaml;
use System\Classes\PluginBase;
use System\Classes\PluginManager;

class Plugin extends PluginBase
{
    public function boot()
    {
        $this->addFormFields();
        $this->allowTranslations();
    }

    private function addFormFields()
    {
        Event::listen('backend.form.extendFieldsBefore', function (Form $form) {
            $theme = Theme::getEditTheme();

            if (!$theme) {
                throw new ApplicationException(__('cms::lang.theme.edit.not_found'));
            }

            // RainLab.Pages plugin
            if (PluginManager::instance()->hasPlugin('RainLab.Pages')
                && $form->model instanceof \RainLab\Pages\Classes\Page) {
                $fields = Yaml::parseFile(plugins_path('magiczne/seotweaker/config/seo_fields.yaml'));

                foreach ($fields as $key => $item) {
                    $form->tabs['fields']["viewBag[{$key}]"] = $item;
                }
            }

            // RainLab.Blog plugin
            if (PluginManager::instance()->hasPlugin('RainLab.Blog')
                && $form->model instanceof \RainLab\Blog\Models\Post) {
                $fields = Yaml::parseFile(plugins_path('magiczne/seotweaker/config/seo_fields.yaml'));

                foreach ($fields as $key => $item) {
                    $item['tab'] = 'SEO';
                    $form->secondaryTabs['fields'][$key] = $item;
                }
            }

            // Default CMS
            if ($form->model instanceof Page) {
                $fields = Yaml::parseFile(plugins_path('magiczne/seotweaker/config/seo_fields.yaml'));

                foreach ($fields as $key => $item) {
                    $form->tabs['fields']["settings[{$key}]"] = $item;
                }
            }
        });
    }

    private function allowTranslations()
    {
        /**
         * Add translatable fields to CMS Page
         */
        Page::extend(function ($page) {
            $page->translatable = array_merge($page->translatable, [
                'seo_keywords', 'seo_canonical_url', 'seo_redirect_url'
            ]);
        });

        /**
         * Add translatable fields to RainLab.Pages plugin page
         */
        if (PluginManager::instance()->hasPlugin('RainLab.Pages')) {
            \RainLab\Pages\Classes\Page::extend(function ($page) {
                $page->translatable = array_merge($page->translatable, [
                    'viewBag[seo_keywords]', 'viewBag[seo_canonical_url]', 'viewBag[seo_redirect_url]'
                ]);
            });
        }

        /**
         * Add translatable fields to RainLab.Blog plugin blog entry
         */
        if (PluginManager::instance()->hasPlugin('RainLab.Blog')) {
            \RainLab\Blog\Models\Post::extend(function ($post) {
                $post->translatable = array_merge($post->translatable, [
                    'seo_keywords', 'seo_canonical_url', 'seo_redirect_url'
                ]);
            });
        }
    }
}
