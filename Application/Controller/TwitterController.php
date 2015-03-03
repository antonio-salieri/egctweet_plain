<?php
namespace Application\Controller;

use Egc\Mvc\View\JsonModel;
use Egc\Service\Twitter\Twitter;
use Egc\Mvc\Application;
class TwitterController extends AbstractController
{

    const STATUS_OK = 'OK';
    const STATUS_ZERO_RESULTS = 'ZERO_RESULTS';
    const STATUS_REQUEST_ERROR = 'REQUEST_ERROR';
    const STATUS_NOT_FOUND = 'NOT_FOUND';
    const STATUS_BAD_REQUEST = 'BAD_REQUEST';

    const VIEW_VAR_STATUS = 'status';
    const VIEW_VAR_ROOT = 'items';
    const VIEW_VAR_TOTAL = 'count';
    const VIEW_VAR_MESSAGE = 'message';

    const REQUEST_QUERY_DATA_NAME = 'data';

	protected $enable_non_xhr_requests = true;

	public function testAction()
	{
	    $config = Application::getConfig();
	    $ts = new Twitter($config['egc_tweet']);
	    var_dump($ts->usersSearch('unclebobmartin'));die;
	}

    protected function getViewModel()
    {
		return new JsonModel();
    }

    protected function prepareViewModel(TwitterResponse $apiResponse)
    {
    	$view = $this->getViewModel();

    	$view->set(self::VIEW_VAR_STATUS, self::STATUS_OK);
//     	if($apiResponse->isError())
//     	{
//     	    $view->setVariable(self::VIEW_VAR_STATUS, self::STATUS_REQUEST_ERROR);
//     	    $view->setVariable(self::VIEW_VAR_MESSAGE, $apiResponse->getErrors());
//     	}
//     	else
//     	{
//     	    $view->setVariable(self::VIEW_VAR_ROOT, $apiResponse->toValue());
//     	    $view->setVariable(self::VIEW_VAR_TOTAL, count($apiResponse->toValue()));
//     	}

//     	return $view;
    }

    protected function getTwitterService()
    {
//     	return $this->getServiceLocator()->get('EgcTwitter');
    }

    public function indexAction()
    {
    	/* @var $response HttpResponse */
//     	$response = $this->getResponse();
//         $response->setStatusCode(Response::STATUS_CODE_500);
//         return $this->getViewModel()->setVariable(self::VIEW_VAR_STATUS, self::STATUS_BAD_REQUEST);
    }

    public function usersAction()
    {
//     	$query = $this->params()->fromRoute(self::REQUEST_QUERY_DATA_NAME);
//         /* @var $twitter \EgcTweet\Service\Twitter */
//         $twitter = $this->getTwitterService();

//         /* @var $apiResponse TwitterResponse */
//         $apiResponse = $twitter->usersSearch($query);

//         $view = $this->prepareViewModel($apiResponse);

//         return $view;
    }

    public function timelineAction()
    {
    	/* @var $twitter \EgcTweet\Service\Twitter */
//     	$twitter = $this->getTwitterService();
//     	$user_id = (int)$this->params()->fromRoute(self::REQUEST_QUERY_DATA_NAME);
//         $tweets = $twitter->getLastTweets($user_id);

//     	$view = $this->prepareViewModel($tweets);

//     	return $view;
    }
}
