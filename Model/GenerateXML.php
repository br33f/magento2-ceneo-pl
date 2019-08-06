<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model;

use DOMDocument;

/**
 * Class GenerateXML
 * @package Orba\Ceneopl\Model
 */
class GenerateXML
{
    /**
     * @var DOMDocument
     */
    protected $dom;

    /**
     * generateXML constructor.
     */
    public function __construct()
    {
        $this->dom = new DOMDocument('1.0', 'utf-8');
    }

    public function generate($array)
    {
        $xml = $this->dom;
        $ns_offers = $xml->createElement('offers');
        $ns_offers->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $ns_offers->setAttribute("version", "1");
        $xml_offers = $xml->appendChild($ns_offers);

        foreach ($array as $group => $o) {

            //element <o>
            foreach ($o as $items => $value) {
                $xml_o = $xml->createElement('o');

                foreach ($this->oChildElementsMapping($value) as $element => $val) {
                    $xml_cat = $xml->createElement($element);
                    $xml_cat->appendChild($xml->createCDATASection($val));
                    $xml_o->appendChild($xml_cat);
                }

                //element <imgs>
                foreach ($this->oChildElementsMappingImgs($value) as $element => $val) {
                    $xml_imgs = $xml->createElement($element);

                    if ($val !== null) {
                        foreach ($val as $key => $url) {
                            $xml_img_main = $xml->createElement($key);
                            $xml_img_main->setAttribute('url', $url);
                            $xml_imgs->appendChild($xml_img_main);
                        }
                    }
                    $xml_o->appendChild($xml_imgs);
                }

                //element <attrs>
                foreach ($this->oChildElementsMappingAttrs($value) as $element => $val) {
                    $xml_attrs = $xml->createElement($element);

                    if ($val !== null) {
                        foreach ($val as $key => $attr) {
                            $xml_attrs_a = $xml->createElement('a');
                            $xml_attrs_a->appendChild($xml->createCDATASection($attr));
                            $xml_attrs_a->setAttribute('name', $key);
                            $xml_attrs->appendChild($xml_attrs_a);
                        }
                    }
                    $xml_o->appendChild($xml_attrs);
                }

                // attributes for element <o>
                foreach ($this->oAtributesMapping($value) as $key => $val) {
                    if ($val !== null) {
                        $xml_o->setAttribute($key, $val);
                    }
                }
                $xml_offers->appendChild($xml_o);
            }
        }
        $xml->appendChild($xml_offers);
        return $xml;

    }

    /**
     * @param $value
     * @return mixed
     */
    public function oChildElementsMapping($value)
    {
        $array = [];
        $array['cat'] = $value['cat']['label'] ?? null;
        $array['name'] = $value['name'] ?? null;
        $array['desc'] = $value['desc'] ?? null;
        return $array;
    }

    public function oChildElementsMappingImgs($value)
    {
        $array = [];
        $array['imgs'] = $value['imgs'] ?? null;
        return $array;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function oChildElementsMappingAttrs($value)
    {
        $array = [];
        $array['attrs'] = $value['group_attrs'] ?? null;
        return $array;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function oAtributesMapping($value)
    {
        $array = [];
        $array['id'] = $value['id'] ?? null;
        $array['url'] = $value['url'] ?? null;
        $array['price'] = $value['price'] ?? null;
        $array['stock'] = $value['core_attrs']['stock'] ?? null;
        $array['avail'] = $value['core_attrs']['avail'] ?? null;
        $array['set'] = $value['core_attrs']['set'] ?? null;
        $array['basket'] = $value['core_attrs']['basket'] ?? null;
        $array['weight'] = $value['core_attrs']['weight'] ?? null;
        return $array;
    }

}