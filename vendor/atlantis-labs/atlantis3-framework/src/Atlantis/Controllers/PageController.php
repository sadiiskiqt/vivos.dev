<?php

namespace Atlantis\Controllers;

use App\Http\Controllers\Controller;
use Atlantis\Models\Interfaces;
use Atlantis\Helpers\Tools;

class PageController extends Controller {

    protected $page;
    protected $pattern;
    protected $lang;
    protected $transport;
    protected $assets;
    protected $currentPage;
    protected $tools;

    public function __construct(Interfaces\IPageInterface $page, Interfaces\IPatternInterface $pattern) {
        $this->page = $page;
        $this->pattern = $pattern;
        $this->tools = new Tools;

        $this->lang = \Route::current()->getParameter('lang') ? \Route::current()->getParameter('lang') : config('atlantis.default_language');

        \Lang::setLocale($this->lang);

        $this->transport = \App::make('Transport');
        $this->assets = \App::make('Assets');

        /**
         * Run the "page.prediscovery" event
         */
        \Event::fire("page.prediscovery", [request()->path()]);

        $this->currentPage = $this->page->findPageByURL(request()->path(), $this->lang);

        if (isset($this->currentPage->protected) && $this->currentPage->protected == 1) {

            $this->middleware('Atlantis\Middleware\SiteAuth');
        }

    }

    public function index(\Request $request) {

        if ($this->currentPage) {

            /** redirect to 404 if page is in dev mode and user is not logged in admin */
            if ($this->currentPage->status == 2) {
                if (auth()->user() == NULL) {
                    return \Redirect('404');
                } else {
                    if (!auth()->user()->hasRole('admin-login')) {
                        return \Redirect('404');
                    }
                }
            }


            /** add tags to page * */
            $this->currentPage->tags = \Atlantis\Models\Repositories\TagRepository::getTagsByResourceIDinArray(Admin\AdminController::$_ID_PAGES, $this->currentPage->id);

            /** Globally share the _page with all the views* */
            view()->share("_page", $this->currentPage);
            view()->share('_storage', $this->tools->getFilePath(FALSE));

            $buildPattern = $this->pattern->buildPatterns($request::path());
            $content = array_merge(($buildPattern ? $buildPattern : []), array("content" => $this->currentPage->page_body));

            $this->transport->setEventValue("page.title", array("title" => $this->currentPage->seo_title, "weight" => 10));
            $this->transport->setEventValue("page.meta_description", array("title" => $this->currentPage->meta_description, "weight" => 10));

            /** Listen to special var for output if no fallback to html  ?_mode=xml , ?_mode=json * */
            $mode = \Input::get('_mode');

            switch ($mode) {
                case "xml" :
                    $output = new \Atlantis\Helpers\OutputFormatter(new \Atlantis\Helpers\Output\Xml, $content);
                    return $output->output();
                    break;
                case "json":
                    $output = new \Atlantis\Helpers\OutputFormatter(new \Atlantis\Helpers\Output\Json, $content);
                    return $output->output();
                    break;
                default:
                    $output = new \Atlantis\Helpers\OutputFormatter(new \Atlantis\Helpers\Output\Html, $content);

                    $this->registerStyles();
                    $this->registerScripts();


                    $body = $output->output($this->currentPage->template);


                    foreach ($body->getData() as $key => $viewData) {
                        $inline = new \Atlantis\Helpers\Dom\Parser($viewData, $this->pattern, $this->tools, config());
                        $body->with($key, $inline->process());
                    }

                    /** Fire the page.title event * */
                    \Event::fire("page.title");
                    /** Fire the page.meta_description event * */
                    \Event::fire("page.meta_description");

                    /** Page title gets  here as a result of the event * */
                    $this->currentPage->seo_title = $this->transport->getEvent('page.title');
                    $this->currentPage->meta_description = $this->transport->getEvent('page.meta_description');

                    \Atlantis\Helpers\Assets::registerHeadTag('<meta name="description" content="' . $this->currentPage->meta_description . '" />');

                    /** Add canonical url to page */
                    if (empty($this->currentPage->canonical_url)) {
                        $canonical_url = url(request()->path());
                    } else {
                        $canonical_url = url($this->currentPage->canonical_url);
                    }
                    \Atlantis\Helpers\Assets::registerHeadTag('<link rel="canonical" href="' . $canonical_url . '" />');

                    /** Fire this event to construct a complex page body element class value * */
                    \Event::fire("page.body_class", [$this->currentPage->url, $this->currentPage->template, $this->currentPage->category_name, $this->currentPage->id]);


                    $pageString = $body->render();

                    /** We fire this event after the entire page has been constructed
                     *   so it can replace the tags in all the possible sources: template, page body, patterns
                     */
                    \Event::fire("page.body", [$pageString]);

                    $pageBodyEventResult = $this->transport->getEvent('page.body');

                    if (!empty($pageBodyEventResult)) {
                        $pageString = $pageBodyEventResult;
                    }

                    /** Fire the page.loaded event * */
                    \Event::fire("page.loaded", [$this->currentPage]);

                    $redirect = app('AtlantisRedirect')->get();

                    if ($redirect != NULL) {
                        return $redirect;
                    }

                    //think for something more elegant
                    if ($request::path() == "404") {
                        return response($pageString, 404);
                    }

                    return $pageString;

                    break;
            }
        } else {
            // 404 here
            //return \Response::view('atlantis::page/404', array($request::path()), 404);
            //this redirects to a regular page with 404 url, however does not send the proper 404 headers
            // needs to be fixed
            $data['results'] = \Atlantis\Models\Repositories\PageRepository::soundex($request::path());
            $data['url'] = $request::path();
            return \Redirect('404?url=' . $request::path())->with('data', $data);
        }

    }

    private function registerStyles() {

        if (config('atlantis.show_shortcut_bar') && auth()->user() != NULL && auth()->user()->hasRole('admin-login')) {
            \Atlantis\Helpers\Assets::registerStyle('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/css/admin-bar.css');
        }

        $styles = config('atlantis.default_styles');
        $mod_path = config('atlantis.theme_path');

        /**
         * default styles
         */
        if (is_array($styles)) {
            foreach ($styles as $style) {
                if (!filter_var($style, FILTER_VALIDATE_URL) === false) {
                    \Atlantis\Helpers\Assets::registerStyle($style);
                } else {
                    \Atlantis\Helpers\Assets::registerStyle($mod_path . '/' . $style);
                }
            }
        }

        /**
         * styles per page
         */
        if (!empty($this->currentPage->styles)) {
            $aStylesPerPage = explode("\n", $this->currentPage->styles);
            foreach ($aStylesPerPage as $stylePerPage) {

                $stylePerPage = trim($stylePerPage);

                if (!filter_var($stylePerPage, FILTER_VALIDATE_URL) === false) {
                    \Atlantis\Helpers\Assets::registerStyle($stylePerPage);
                } else {
                    \Atlantis\Helpers\Assets::registerStyle($mod_path . '/' . $stylePerPage);
                }
            }
        }

    }

    private function registerScripts() {

        $scripts = config('atlantis.default_scripts');
        $mod_path = config('atlantis.theme_path');

        /**
         * default scripts
         */
        if (is_array($scripts)) {
            foreach ($scripts as $script) {
                if (!filter_var($script, FILTER_VALIDATE_URL) === false) {
                    \Atlantis\Helpers\Assets::registerScript($script);
                } else {
                    \Atlantis\Helpers\Assets::registerScript($mod_path . '/' . $script);
                }
            }
        }

        /*
         * scripts per page
         */
        if (!empty($this->currentPage->scripts)) {
            $aScriptsPerPage = explode("\n", $this->currentPage->scripts);
            foreach ($aScriptsPerPage as $scriptPerPage) {

                $scriptPerPage = trim($scriptPerPage);

                if (!filter_var($scriptPerPage, FILTER_VALIDATE_URL) === false) {
                    \Atlantis\Helpers\Assets::registerScript($scriptPerPage);
                } else {
                    \Atlantis\Helpers\Assets::registerScript($mod_path . '/' . $scriptPerPage);
                }
            }
        }

    }

}
