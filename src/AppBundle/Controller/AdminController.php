<?php

namespace AppBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class AdminController extends BaseAdminController
{
    public function createNewUserEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    public function prePersistUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }

    public function preUpdateUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }

    public function preUpdateEntity($entity)
    {
        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTime());
        }

 //       $this->updateSlug($entity);
    }

//    public function prePersistEntity($entity)
//    {
//        $this->updateSlug($entity);
//    }
//
//
//    private function updateSlug($entity)
//    {
//        if (method_exists($entity, 'setSlug') and method_exists($entity, 'getTitle')) {
//            $entity->setSlug($this->get('app.slugger')->slugify($entity->getTitle()));
//        }
//    }

}
