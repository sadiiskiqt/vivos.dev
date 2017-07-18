<?php

namespace Atlantis\Helpers\Dom;

class Parser {

    public $document;
    public $items;
    private $dataPatternID = 'data-pattern-id';
    private $dataPatternName = 'data-pattern-name';
    private $dataPatternFunc = 'data-pattern-func';
    private $dataStorage = 'data-storage';
    private $dataNoMobile = 'data-nomobile';
    private $dataRawHTML = 'data-raw-html';
    private $dataResponsive = 'data-responsive';
    private $config;
    private $pattern;
    private $tools;
    private $wasEdited = FALSE;

    public function __construct($doc, \Atlantis\Models\Repositories\PatternRepository $pattern, \Atlantis\Helpers\Tools $tools, $config) {

        $this->config = $config;

        $this->document = new \DOMDocument();

        @$this->document->loadHTML(mb_convert_encoding($doc, 'HTML-ENTITIES', 'UTF-8'));

        $this->document->formatOutput = true;

        $this->items = $this->getAllElements();

        $this->pattern = $pattern;

        $this->tools = $tools;

    }

    public function getAllElements() {

        return $this->document->getElementsByTagName('*');

    }

    public function process() {

        $p = new $this->pattern();
        $storagePath = rtrim($this->tools->getFilePath(FALSE), '/');

        $this->wasEdited = FALSE;

        for ($i = 0; $i < $this->items->length; $i++) {

            if ($this->items->item($i)->hasAttribute($this->dataPatternID)) {

                $id = $this->items->item($i)->getAttribute($this->dataPatternID);

                $pattern = head($p->processPatterns([$id], 'id'));

                if ($pattern) {
                    $this->items->item($i)->nodeValue = $pattern;
                } else {
                    //abort(404, 'Pattern id not found');
                }

                if ($this->items->item($i)->removeAttribute($this->dataPatternID)) {
                    $this->wasEdited = TRUE;
                }
            } else if ($this->items->item($i)->hasAttribute($this->dataPatternName)) {

                $name = $this->items->item($i)->getAttribute($this->dataPatternName);

                $pattern = head($p->processPatterns([$name], 'name'));

                if ($pattern) {
                    $this->items->item($i)->nodeValue = $pattern;
                } else {
                    //abort(404, 'Pattern name not found');
                }

                if ($this->items->item($i)->removeAttribute($this->dataPatternName)) {
                    $this->wasEdited = TRUE;
                }
            } else if ($this->items->item($i)->hasAttribute($this->dataPatternFunc)) {

                $aAttr = array();

                foreach ($this->items->item($i)->attributes as $attr) {
                    $aAttr[$attr->nodeName] = $attr->nodeValue;
                }

                unset($aAttr[$this->dataPatternFunc]);

                //dd($aAttr);

                $this->items->item($i)->nodeValue = $this->tools->makeAppCallFromString($this->items->item($i)->getAttribute($this->dataPatternFunc), $aAttr);

                if ($this->items->item($i)->removeAttribute($this->dataPatternFunc)) {
                    $this->wasEdited = TRUE;
                }
            } else if ($this->items->item($i)->hasAttribute($this->dataStorage) && $this->items->item($i)->hasAttribute($this->dataStorage)) {

                if ($this->items->item($i)->nodeName == 'img') {
                    $this->items->item($i)->setAttribute('src', $storagePath . $this->items->item($i)->getAttribute('src'));
                } else {
                    $this->items->item($i)->setAttribute('src', $storagePath . $this->items->item($i)->getAttribute('href'));
                }

                if ($this->items->item($i)->removeAttribute($this->dataStorage)) {
                    $this->wasEdited = TRUE;
                }
            } else if ($this->items->item($i)->hasAttribute($this->dataNoMobile)) {

                $device = \App::make('MobileDetect');
                if ($device->isMobile()) {
                    $this->items->item($i)->parentNode->removeChild($this->items->item($i));
                    $this->wasEdited = TRUE;
                }
            } else if ($this->items->item($i)->hasAttribute($this->dataRawHTML)) {

                $innerHTML = "";
                $children = $this->items->item($i)->childNodes;
                foreach ($children as $child) {

                    $innerHTML .= $this->items->item($i)->ownerDocument->saveHTML($child);
                }

                if ($this->items->item($i)->getAttribute($this->dataRawHTML) != 'checked') {
                    $innerHTML = htmlentities($innerHTML, ENT_QUOTES, 'UTF-8');
                }

                $this->items->item($i)->textContent = $innerHTML;

                $this->items->item($i)->setAttribute($this->dataRawHTML, 'checked');
            } else if ($this->items->item($i)->hasAttribute($this->dataResponsive)) {
                
                $this->items->item($i)->removeAttribute($this->dataResponsive);
                
                $src = $this->items->item($i)->getAttribute('src');

                $filename = basename($src);
             
                if (!empty($filename)) {
                    $media = \Atlantis\Helpers\Media\MediaTools::findByName($filename);
                    
                    if (!empty($media) && !empty($media->tablet_name) && !empty($media->phone_name) && isset($this->config['atlantis']['responsive_breakpoints']['large']) && isset($this->config['atlantis']['responsive_breakpoints']['medium'])) {
                        
                        $imgTag = clone $this->items->item($i);
                        
                        $picTag = $this->document->createElement('picture');

                        $sourceTagSmall = $this->document->createElement('source');
                        $sourceTagSmall->setAttribute('media', '(max-width: ' . $this->config['atlantis']['responsive_breakpoints']['medium'] . 'px)');
                        $sourceTagSmall->setAttribute('srcset', $media->phone_name . ', ' . $media->tablet_name . ' 2x');

                        $sourceTagMedium = $this->document->createElement('source');
                        $sourceTagMedium->setAttribute('media', '(min-width: ' . $this->config['atlantis']['responsive_breakpoints']['medium'] . 'px)');
                        $sourceTagMedium->setAttribute('srcset', $media->tablet_name . ', ' . $media->original_filename . ' 2x');

                        $sourceTagLarge = $this->document->createElement('source');
                        $sourceTagLarge->setAttribute('media', '(min-width: ' . $this->config['atlantis']['responsive_breakpoints']['large'] . 'px)');
                        $sourceTagLarge->setAttribute('srcset', $media->original_filename . ', ' .$media->original_filename . ' 2x');

                        $picTag->appendChild($sourceTagSmall);
                        $picTag->appendChild($sourceTagMedium);
                        $picTag->appendChild($sourceTagLarge);
                        $picTag->appendChild($imgTag);
                        $this->items->item($i)->parentNode->replaceChild($picTag, $this->items->item($i));
                        
                    }
                }
            }
        }

        return $this->output();

    }

    public function output() {

        if ($this->wasEdited) {

            $doc = html_entity_decode($this->document->saveHTML(), ENT_QUOTES, 'UTF-8');

            $this->document = new \DOMDocument();

            @$this->document->loadHTML(mb_convert_encoding($doc, 'HTML-ENTITIES', 'UTF-8'));

            $this->document->formatOutput = true;

            $this->items = $this->getAllElements();

            return $this->process();
        }

        if ($this->document->hasChildNodes() && $this->document->nodeName == '#document') {
            /** remove <!DOCTYPE */
            $this->document->removeChild($this->document->firstChild);

            if ($this->document->hasChildNodes() && $this->document->firstChild->nodeName == 'html') {
                /** remove <html> */
                $this->document->replaceChild($this->document->firstChild->firstChild, $this->document->firstChild);

                /**
                 * get all child tags in <body> and append in new document
                 */
                $mock = new \DOMDocument();

                foreach ($this->document->firstChild->childNodes as $child) {
                    @$mock->appendChild($mock->importNode($child, true));
                }

                $this->document = $mock;
            }
        }


        /** save new document without <body> */
        $out = $this->document->saveHTML();

        return html_entity_decode($out, ENT_QUOTES, 'UTF-8');

    }

}
