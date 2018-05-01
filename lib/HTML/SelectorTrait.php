<?php
/*
Copyright 2015 Lcf.vs
Copyright © 2008 - 2009 TJ Holowaychuk <tj@vision-media.ca>
 -
Released under the MIT license
 -
https://github.com/Lcfvs/PHPDOM
*/
namespace PHPDOM\HTML;

trait SelectorTrait
{
    public function query($query)
    {
        $response = $this->ownerDocument->xpath->evaluate($query, $this);

        if ($response instanceof \DOMNodeList) {
            return new NodeList($response);
        }

        return $response;
    }

    public function select($selector)
    {
        $node_list = $this->selectAll($selector);

        if ($node_list instanceof NodeList) {
            return $node_list->item(0);
        }
    }

    public function selectAll($selector)
    {
        $query = self::_parse($selector);
        
        if ($this instanceof DocumentFragment && $this->parent) {
            $query = preg_replace('/(^|\|)(descendant(?:-or-self)::)/', '\1/\2', $query);
        }
        
        return $this->query($query);
    }

    private static function _parse($selector)
    {
        $query = SelectorCache::get($selector);
        
        if ($query) {
            return $query;
        }

        $query = \PhpCss::toXpath($selector);

        return SelectorCache::set($selector, $query);
    }
}