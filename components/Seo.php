<?php namespace Magiczne\SeoTweaker\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Event;
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
    public $ogImage;
    public $ogImageWidth;
    public $ogImageHeight;

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

        Event::fire('seotweaker.beforeComponentRender', [$this, $this->page]);
    }

    private function getDataFromBlogPost()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Blog')) {
            $post = $this->page->post;

            $this->title = $post->title;
            $this->description = $post->excerpt;
            $this->keywords = $post->seo_keywords;
            // The seo_canonical_url saves empty anyway so we need to check 
            // for it instead of relying on its (non)existence
            if ( !empty($post->seo_canonical_url) ) {
                $this->canonicalUrl = $post->seo_canonical_url;
            } else {
                $this->canonicalUrl = Request::url();
            }            
            $this->redirectUrl = $post->seo_redirect_url;
            $this->robotsIndex = $post->seo_robots_index;
            $this->robotsFollow = $post->seo_robots_follow;

            $featuredImage = $post->featured_images->first();
            if ($featuredImage) {
                $this->ogImage = $featuredImage->path;
                $localPath = $featuredImage->getLocalPath();
                if (is_file($localPath)) {
                    [$width, $height] = getimagesize($localPath);
                    $this->ogImageWidth = $width;
                    $this->ogImageHeight = $height;
                }
            }
        }
    }

    private function getDataFromStaticPage()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Pages')) {
            $url = Request::path();

            // If RainLab.Translate plugin is installed we need to ensure, that
            // lang prefix is not in our way, so we are removing it from url.
            if (PluginManager::instance()->hasPlugin('RainLab.Translate')) {
                // Remove language prefix from URL to get clean URL
                // Simply exploding the URL after first slash does not work for 
                // multilevel websites
                $url = substr($url, 3); 

                // If we're in root then use root as URL
                if (!strlen($url))
                    $url = '/';
            }

            $router = new Router(Theme::getActiveTheme());
            $page = $router->findByUrl($url);
            // Proper way of obtaining localized/translated viewBag data from 
            // Static page. According to documentation the getViewBag() method 
            // is for internal use only.
            $viewBag = $page->viewBag;

            // Updated to reflect change from $page->getViewBag() to $page->viewBag 
            // which is an array now
            $this->title = $viewBag['meta_title'] ?? $viewBag['title'];
            $this->description = $viewBag['meta_description'] ?? NULL;
            $this->keywords = $viewBag['seo_keywords'] ?? NULL;
            $this->canonicalUrl = $viewBag['seo_canonical_url'] ?? Request::url();
            $this->redirectUrl = $viewBag['seo_redirect_url'] ?? NULL;
            $this->robotsIndex = $viewBag['seo_robots_index'] ?? NULL;
            $this->robotsFollow = $viewBag['seo_robots_follow'] ?? NULL;
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
