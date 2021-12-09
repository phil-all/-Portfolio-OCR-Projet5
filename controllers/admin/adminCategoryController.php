<?php

namespace Over_Code\Controllers\Admin;

use Over_Code\Models\ArticlesModel;
use Over_Code\Models\CategoryModel;
use Over_Code\Controllers\MainController;

/**
 * Admin categories controller
 */
class AdminCategoryController extends MainController
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Set template for category list
     *
     * @param array $params
     *
     * @return void
     */
    public function liste(array $params)
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $paramsTest = count($params) === 1 && $params[0] === $this->getCOOKIE('CSRF');
        
        if ($this->userToTwig['admin'] && $paramsTest) {
            $category   = new CategoryModel();
            $categories = $category->readAll();

            $this->params['categories'] = $categories;

            $this->userToTwig['template'] = 'admin';

            $this->preventCsrf();
            
            $this->template = $this->template = 'admin' . DS . 'categories-read.twig';
        }
    }

    /**
     * Set template form to create new category
     *
     * @param array $params
     *
     * @return void
     */
    public function nouvelle(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $paramsTest = count($params) === 1 && $params[0] === $this->getCOOKIE('CSRF');
        
        if ($this->userToTwig['admin'] && $paramsTest) {
            $this->userToTwig['template'] = 'admin';

            $this->preventCsrf();
            
            $this->template = $this->template = 'admin' . DS . 'categories-new.twig';
        }
    }

    /**
     * Process to new category creation
     *
     * @param array $params
     *
     * @return void
     */
    public function newProcess(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $paramsTest = count($params) === 1 && $params[0] === $this->getCOOKIE('CSRF');
        
        if ($this->userToTwig['admin'] && $paramsTest) {
            $this->template = 'admin' . DS . 'categories-allready-exist.twig';

            $post = ucfirst(strtolower($this->getPOST('category')));

            $category = new CategoryModel();
            
            if (!$category->isEXist($post)) {
                $category->create($post);

                $this->redirect(SITE_ADRESS . '/adminCategory/liste/' . $this->getCOOKIE('CSRF'));
            }
        }
    }

    /**
     * Set template to modify a given category
     *
     * @param array $params
     * @return void
     */
    public function modifier(array $params)
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $category = new CategoryModel();

        $paramsTest = count($params) === 3 &&
            $category->isExist($params[1]) &&
            $params[1] !== 'aucune' &&
            $params[2] === $this->getCOOKIE('CSRF');

        if ($this->userToTwig['admin'] && $paramsTest) {
            $this->userToTwig['template'] = 'admin';

            $this->params = [
                'id'       => $params[0],
                'category' => $params[1]
            ];

            $this->preventCsrf();

            $this->template = 'admin' . DS . 'categories-update.twig';
        }
    }

    /**
     * Process to update a given category
     *
     * @param array $params
     *
     * @return void
     */
    public function updateProcess(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $paramsTest = count($params) === 2 && $params[1] === $this->getCOOKIE('CSRF');
        
        if ($this->userToTwig['admin'] && $paramsTest) {
            $this->template = 'admin' . DS . 'categories-allready-exist.twig';

            $post = ucfirst(strtolower($this->getPOST('category')));

            $category = new CategoryModel();

            if (!$category->isEXist($post)) {
                $category->update((int)$params[0], $post);
    
                $this->redirect(SITE_ADRESS . '/adminCategory/liste/' . $this->getCOOKIE('CSRF'));
            }
        }
    }

    /**
     * Process to delete a given, category and update its aricles on category
     * id 1, wich correspond to uncategorized
     *
     * @param array $params array from uri :
     * - $params[0] is an integer wich correspond to category id
     * - $params[1] is a string which correspond to a category name
     *
     * @return void
     */
    public function delete(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $category = new CategoryModel();

        $paramsTest = count($params) === 3 &&
            $category->isExist($params[1]) &&
            $params[1] !== 'aucune' &&
            $params[2] === $this->getCOOKIE('CSRF');

        if ($this->userToTwig['admin'] && $paramsTest) {
            $article = new ArticlesModel();
            $article->uncategorized((int)$params[0]);

            $category->delete((int)$params[0]);

            $this->redirect(SITE_ADRESS . '/adminCategory/liste/' . $this->getCOOKIE('CSRF'));
        }
    }
}
