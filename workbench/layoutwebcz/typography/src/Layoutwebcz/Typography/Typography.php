<?php namespace Layoutwebcz\Typography;

use Illuminate\View\View;

class Typography {

    private $app = null;

    public function __construct($app = null)
    {
        $this->app = $app;
    }

    /**
     * Alert info message
     * @param string $message
     * @param bool $icon
     * @return mixed
     */
    public function alert_info($message = '', $icon = true)
    {
        return $this->app->view->make('typography::alert_info')
            ->with('message', $message)
            ->with('icon', $icon)
            ->render();
    }

    /**
     * Alert danger message
     * @param string $message
     * @param bool $icon
     * @return mixed
     */
    public function alert_danger($message = '', $icon = true)
    {
        return $this->app->view->make('typography::alert_danger')
            ->with('message', $message)
            ->with('icon', $icon)
            ->render();
    }

    /**
     * Link button
     * @param string $url
     * @param string $message
     * @param string $class
     * @param string $icon
     * @param array $attributes
     * @return $this|\Illuminate\View\View
     */
    public function link_button($url = '', $message = '', $class = '', $icon = '', $attributes = array())
    {
        if($attributes)
        {
            foreach($attributes as $key=>$value)
            {
                $attributes[] = "$key=\"$value\"";

                unset($attributes[$key]);
            }
        }

        return
            $this->app->view->make('typography::link_button')
                ->with('url', $url)
                ->with('message', $message)
                ->with('class', $class)
                ->with('icon', $icon)
                ->with('attributes', implode(' ', $attributes))
                ->render();
    }

    /**
     * Form button
     * @param string $type
     * @param string $text
     * @param string $class
     * @param string $icon
     * @param array $attributes
     * @return $this|\Illuminate\View\View
     */
    public function form_button($type = '', $text = '', $class = '', $icon = '', $attributes = array())
    {
        if($attributes)
        {
            foreach($attributes as $key=>$value)
            {
                $attributes[] = "$key=\"$value\"";

                unset($attributes[$key]);
            }
        }

        return
            $this->app->view->make('typography::form_button')
                ->with('type', $type)
                ->with('text', $text)
                ->with('class', $class)
                ->with('icon', $icon)
                ->with('attributes', implode(' ', $attributes))
                ->render();
    }

    /**
     * UI Link button
     * @param string $url
     * @param string $message
     * @param array $data
     * @param array $attributes
     * @return $this|\Illuminate\View\View
     */
    public function link_ui_button($url = '', $message = '', $data = array(), $attributes = array())
    {
        if($attributes)
        {
            foreach($attributes as $key=>$value)
            {
                $attributes[] = "$key=\"$value\"";

                unset($attributes[$key]);
            }
        }

        return
            $this->app->view->make('typography::ui/link_button')
                ->with('url', $url)
                ->with('message', $message)
                ->with('data', $data)
                ->with('attributes', implode(' ', $attributes))
                ->render();
    }

    /**
     * Form button
     * @param string $type
     * @param string $value
     * @param array $data
     * @param array $attributes
     * @return $this|\Illuminate\View\View
     */
    public function form_ui_button($type = '', $value = '', $data = array(), $attributes = array())
    {
        if($attributes)
        {
            foreach($attributes as $key=>$value)
            {
                $attributes[] = "$key=\"$value\"";

                unset($attributes[$key]);
            }
        }

        return
            $this->app->view->make('typography::ui.form_button')
                ->with('type', $type)
                ->with('value', $value)
                ->with('data', $data)
                ->with('attributes', implode(' ', $attributes))
                ->render();
    }
}