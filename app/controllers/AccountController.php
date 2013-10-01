<?php

use CustomHelpers\CustomLanguageHelper;

class AccountController extends BaseController {

	public function getIndex()
	{
        $view = View::make('account.index');

        $this->layout->document_title = Lang::get('account.document_title');
        $this->layout->article_title = Lang::get('account.article_title');

        $view->user = json_decode(APP_USER);

        $view->languages = CustomLanguageHelper::getListOfLanguages();

        $this->layout->content = $view;
	}

    /**
     * Get login page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogin()
    {
        // user already logged in
        if (Sentry::check())
        {
            return Redirect::to('/');
        }

        $view = View::make('account.login');

        View::share('languages', CustomLanguageHelper::getListOfLanguages());

        $this->layout->document_title = Lang::get('account.document_title');
    }

    /**
     * Login user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function postLogin()
    {
        $validator = Validator::make(
            array(
                'email' => Input::get('email'),
                'password' => Input::get('password'),
                'locale' => Input::get('locale', Config::get('app.locale')),
            ),
            array(
                'email' => 'required',
                'password' => 'required'
            )
        );

        $message = array('type' => '', 'message' => '');

        if ($validator->fails())
        {
            $message['type'] = 'error';
            $message['message'] =  Lang::get('account.error.credentials.missing_all');
        }
        else
        {
            $credentials = $validator->getData();
            $locale = $credentials['locale'];

            unset($credentials['locale']);

            try
            {
                // Try to authenticate the user
                $user = Sentry::authenticate($credentials, false);
            }
            catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
            {
                $message['type'] = 'error';
                $message['message'] =  Lang::get('account.error.credentials.missing_loginname');
            }
            catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
            {
                $message['type'] = 'error';
                $message['message'] =  Lang::get('account.error.credentials.missing_password');
            }
            catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
            {
                $message['type'] = 'error';
                $message['message'] =  Lang::get('account.error.credentials.wrong_password');
            }
            catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
            {
                $message['type'] = 'error';
                $message['message'] = Lang::get('account.error.credentials.not_found');
            }
            catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
            {
                $message['type'] = 'error';
                $message['message'] =  Lang::get('account.error.credentials.not_activated');
            }
            /*
            // The following is only required if throttle is enabled
            catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
            {
                $message = 'User is suspended.';
            }
            catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
            {
                $message = 'User is banned.';
            }
            */
            catch (Exception $e)
            {
                $message['type'] = 'error';
                $message['message'] =  Lang::get('shared.error.uknown');
            }
        }

        $intended_url = Session::get('url.intended', '');

        if (!$message['type'])
        {
            // login success
            $message['type'] = 'success';

            if ($intended_url)
            {
                $request = Request::create($intended_url);

                $intended_url_segments = $request->segments();

                if (isset($intended_url_segments[0]))
                {
                    $intended_url_segments[0] = $locale;

                    $intended_url = URL::to(implode('/', $intended_url_segments));
                }
            }
            else
            {
                // default index /lang URL
                $intended_url = URL::to($locale);
            }

            $message['message'] =  $intended_url;
        }

        return Response::json($message);

    }

    /**
     * Logout currently logged in user - GET request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {
        if (!Sentry::check())
        {
            // user is not logged in, why to logout again, hacker?
            return Redirect::to('/');
        }

        Sentry::logout();
        return Redirect::to('/');
    }

    /**
     * Logout currently logged in user - POST request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogout()
    {
        $this->getLogout();
    }

    /**
     * Update user details
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSaveAccount()
    {
        $validator = Validator::make(
            array(
                'id' => (int)(Input::get('id')),
                'email' => strip_tags(Input::get('email')),
                'details' => (array)Input::get('details'),
            ),
            array(
                'id' => array('required', 'integer'),
                'email' => array('required', 'email'),
                'details' => array('required'),
            ),
            array(
                'id.required' => Lang::get('account.edit.error.required.id'),
                'id.integer' => Lang::get('account.edit.error.integer.id'),
                'email.required' => Lang::get('account.edit.error.required.email'),
                'email.email' => Lang::get('account.edit.error.email.email'),
                'details.required' => Lang::get('account.edit.error.required.details'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        $details = $data['details'];
        unset($data['details']);

        // update #__user table
        try
        {
            // Find the user using the user id
            $user = Sentry::getUserProvider()->findById($data['id']);

            // check if user is edited his own account
            if(APP_USER_ID !== (int)$user->id)
            {
                return Redirect::back()->with('error', Lang::get('shared.error.not_auth'));
            }

            // Update the user details
            $user->email = $data['email'];

            // Update the user
            if (!$user->save())
            {
                return Redirect::back()->with('error', Lang::get('shared.error.db.update'));
            }
        }
        catch (Cartalyst\Sentry\Users\UserExistsException $e)
        {
            return Redirect::back()->with('error', Lang::get('account.edit.status.error.user.exists', array('email' => $data['email'])));
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            return Redirect::back()->with('error', Lang::get('account.edit.status.error.user.not_found'));
        }

        if($details)
        {
            if(!isset($details['language']))
            {
                $details['language'] = 'en';
            }

            if(!$user->details->update($details))
            {
                return Redirect::back()->with('error', Lang::get('shared.error.db.update'));
            }
        }

        return Redirect::back()->with('success', Lang::get('account.edit.status.success'));
    }
}
