<?php

namespace Application\Controller;


use Egc\Mvc\View;
class ProfileController extends AbstractController {

	public function indexAction() {

// 		$profileTable = $this->getProfileTable();
// 		$identity = $this->zfcUserAuthentication()->getIdentity();
// 		if (!$identity)
// 		{
// 			return $this->redirect()->toRoute(UserController::ROUTE_LOGIN);
// 		}

// 		$followings = $profileTable->getUserFollowings($identity->getId());

// 		$form = new ProfileForm();
// 		$form->bind($followings);

// 		return new ViewModel(array('form' => $form));
        $view = new View('profile/index.phtml');
//         $view->set('followings', );
        return $view->render();
	}

	public function saveAction() {

// 		$identity = $this->zfcUserAuthentication()->getIdentity();
// 		if (!$identity)
// 		{
// 			return $this->redirect()->toRoute(UserController::ROUTE_LOGIN);
// 		}

// 		$form = new ProfileForm();
// 		$profileTable = $this->getProfileTable();
// 		$form->setData($this->getRequest()->getPost());
// 		if ($form->isValid())
// 		{
// 			$data = $form->getData();
// 			$collection = new FollowingCollection($data[ProfileForm::FOLLOWINGS_FIELDSET_NAME]);
//             $user_id = $identity->getId();

//             foreach ($collection as $item) {
//                 $following_name = $item->getFollowingName();
//                 if ($item->getFollowingId() && $following_name)
//                     $profileTable->saveFollowing($item, $user_id);
//                 else if($item->getId() && empty($following_name))
//                     $profileTable->deleteFollowing($item->getId(), $user_id);
// 			}
// 		}

// 		return $this->redirect()->toRoute('home');
	}
}
