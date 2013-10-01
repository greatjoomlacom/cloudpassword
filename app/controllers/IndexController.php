<?php

use \CustomHelpers\ArrayHelper;

class IndexController extends BaseController {

	public function getIndex()
	{
        $view = View::make('index');

        $this->layout->document_title = Lang::get('index.document_title');
        $this->layout->article_title = Lang::get('index.article_title');
        $this->layout->article_title_icon = Lang::get('index.article_title_icon');

        $MenuModel = new MenuModel();

        $view->dashboard_items = $MenuModel->getMenuItems('dashboard');

        $this->layout->content = $view;
	}

    /**
     * Set ordering to session
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSetOrdering()
    {
        $validator = Validator::make(
            array(
                'ordering' => (array)Input::get('ordering', array()),
            ),
            array(),
            array()
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        Session::set('ordering', $data['ordering']);

        return Redirect::back();
    }

    /**
     * Set filter to session
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSetFilter()
    {
        $validator = Validator::make(
            array(
                'filter' => (array)Input::get('filter', array()),
            ),
            array(),
            array()
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        // new filter
        if(!Session::has('filter'))
        {
            Session::set('filter', $data['filter']);
            return Redirect::back();
        }

        $old_filter = (array)Session::get('filter', array());

        $filter = ArrayHelper::array_merge_recursive_distinct($old_filter, $data['filter']);
        $filter = ArrayHelper::removeEmptyFromArray($filter);

        if($filter)
        {
            Session::set('filter', $filter);
        }
        else
        {
            Session::remove('filter');
        }

        $url = strtok((URL::previous() ? URL::previous() : URL::full()), '?');

        return Redirect::to($url);
    }

}