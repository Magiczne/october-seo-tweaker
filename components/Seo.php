<?php namespace Magiczne\SeoTweaker\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Str;
use RainLab\Pages\Classes\Router;
use Cms\Classes\Theme;
use Illuminate\Support\Facades\Request;
use System\Classes\PluginManager;

class Seo extends ComponentBase
{
    public $title;
    public $description;
    public $keywords;
    public $canonicalUrl;
    public $redirectUrl;
    public $robotsFollow;
    public $robotsIndex;

    public function componentDetails()
    {
        return [
            'name' => 'magiczne.seotweaker::lang.components.seo.name',
            'description' => 'magiczne.seotweaker::lang.components.seo.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'includeOpenGraph' => [
                'title' => 'magiczne.seotweaker::lang.components.seo.properties.include_open_graph.title',
                'type' => 'checkbox',
                'default' => true
            ],
            'includeTwitter' => [
                'title' => 'magiczne.seotweaker::lang.components.seo.properties.include_twitter.title',
                'type' => 'checkbox',
                'default' => true
            ],
            'includeJsonLd' => [
                'title' => 'magiczne.seotweaker::lang.components.seo.properties.include_json_ld.title',
                'type' => 'checkbox',
                'default' => true
            ]
        ];
    }

    public function onRender()
    {
        $isBlogPage = $this->page->page->hasComponent('blogPost');
        $isStaticPage = $this->page->layout->hasComponent('staticPage');

        if ($isBlogPage !== false) {
            $this->getDataFromBlogPost();
        } else if ($isStaticPage !== false) {
            $this->getDataFromStaticPage();
        } else {
            $this->getDataFromCmsPage();
        }
    }

    private function getDataFromBlogPost()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Blog')) {
            $post = $this->page->post;

            $this->title = $post->title;
            $this->description = $post->excerpt;
            $this->keywords = $post->seo_keywords;
            $this->canonicalUrl = $post->seo_canonical_url ?? Request::url();
            $this->redirectUrl = $post->seo_redirect_url;
            $this->robotsIndex = $post->seo_robots_index;
            $this->robotsFollow = $post->seo_robots_follow;
        }
    }

    private function getDataFromStaticPage()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Pages')) {
            $url = Request::path();

            // If RainLab.Translate plugin is installed we need to ensure, that
            // lang prefix is not in our way, so we are removing it from url.
            if (PluginManager::instance()->hasPlugin('RainLab.Translate')) {
                if (Str::contains($url, '/')) {
                    $url = explode('/', $url)[1];
                }
            }

            $router = new Router(Theme::getActiveTheme());
            $page = $router->findByUrl($url);
            $viewBag = $page->getViewBag();

            $this->title = $viewBag->meta_title ?? $viewBag->title;
            $this->description = $viewBag->meta_description;
            $this->keywords = $viewBag->seo_keywords;
            $this->canonicalUrl = $viewBag->seo_canonical_url ?? Request::url();
            $this->redirectUrl = $viewBag->seo_redirect_url;
            $this->robotsIndex = $viewBag->seo_robots_index;
            $this->robotsFollow = $viewBag->seo_robots_follow;
        }
    }

    private function getDataFromCmsPage()
    {
        $this->title = $this->page->meta_title ?? $this->page->title;
        $this->description = $this->page->meta_description ?? $this->page->description;
        $this->keywords = $this->page->seo_keywords;
        $this->canonicalUrl = $this->page->seo_canonical_url ?? Request::url();
        $this->redirectUrl = $this->page->seo_redirect_url;
        $this->robotsIndex = $this->page->seo_robots_index;
        $this->robotsFollow = $this->page->seo_robots_follow;
    }
}
