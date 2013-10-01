<?php

namespace CustomHelpers;

use View;
use Session;

class CustomViewHelper {

    /**
     * Ordering
     * @param string $context
     * @param string $subject
     * @param array $ordering "subject" (aka name, email, date...) and "direction" (ASC, DESC)
     * @return bool|string
     */
    public static function orderingView($context = '', $subject = '', $ordering = array())
    {
        if(!$context) return false;
        if(!$subject) return false;

        $view = View::make('shared.ordering');

        $view->context = $context;
        $view->subject = $subject;
        $view->ordering = $ordering;

        return $view->render();
    }

    /**
     * Ordering
     * @param string $context
     * @param array $advanced
     * @return bool|string
     */
    public static function filterView($context = '', $advanced = array())
    {
        if(!$context) return false;

        $view = View::make('shared.filter');

        $view->context = $context;

        $filter = (array)Session::get("filter", array());
        $view->filter = (isset($filter[$context]) ? $filter[$context] : array());

        $view->advanced = $advanced;

        return $view->render();
    }
}
