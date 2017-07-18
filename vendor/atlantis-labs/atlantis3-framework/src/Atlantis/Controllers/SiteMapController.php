<?php

namespace Atlantis\Controllers;

use App\Http\Controllers\Controller;

class SiteMapController extends Controller {

  public function index() {

    $dom = new \DOMDocument('1.0', 'utf-8');

    $documentNode = $dom->appendChild($dom->createElement("urlset"));
    $documentNode->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");

    $repo = new \Atlantis\Models\Repositories\PageRepository;

    $pages = $repo->getSiteMapPages();

    $aResults = array();

    \Event::fire('sitemap.providers', []);

    $t = \App::make('Transport');

    $sitemaphModels = $t->getEvent('sitemap.providers', TRUE);

    foreach ($pages as $page) {
      $aResults[] = [
          'url' => $page->url,
          'updated_at' => $page->updated_at
      ];
    }

    foreach ($sitemaphModels as $model) {

      $aResults = array_merge($aResults, $model::get());
    }

    foreach ($aResults as $res) {

      $url = $documentNode->appendChild($dom->createElement("url"));

      $loc = $url->appendChild($dom->createElement('loc'));

      if ($res['url'] == "/") {
        $loc->nodeValue = url('/');
      } else {

        $res['url'] = ltrim($res['url'], '/');

        $loc->nodeValue = url($res['url']);
      }

      $lastmod = $url->appendChild($dom->createElement('lastmod'));

      if (!is_a($res['updated_at'], \Carbon\Carbon::class)) {

        $updated_at = \Carbon\Carbon::createFromFormat(\Carbon\Carbon::DEFAULT_TO_STRING_FORMAT, $res['updated_at']);
      } else {
        $updated_at = $res['updated_at'];
      }

      $lastmod->nodeValue = $updated_at->format(\Carbon\Carbon::W3C);

      $changefreq = $url->appendChild($dom->createElement('changefreq'));

      $changefreq->nodeValue = "weekly";

      $priority = $url->appendChild($dom->createElement('priority'));

      $priority->nodeValue = "0.5";
    }

    return \Response::make($dom->saveXML(), '200')->header('Content-Type', 'text/xml');
  }

}
