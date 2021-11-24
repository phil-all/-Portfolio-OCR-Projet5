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
     * @return void
     */
    public function index()
    {
        $this->template = 'client' . DS . 'accueil.twig';
        
        if ($this->userToTwig['admin']) {
            $category = new CategoryModel();
            $categories = $category->readAll();

            $this->params = [
                'categories' => $categories
            ];

            $this->userToTwig['template'] = 'admin';
            
            $this->template = $this->template = 'admin' . DS . 'categories-read.twig';
        }
    }

    /**
     * Set template form to create new category
     *
     * @return void
     */
    public function nouvelle(): void
    {
        $this->template = 'client' . DS . 'accueil.twig';
        
        if ($this->userToTwig['admin']) {
            $this->userToTwig['template'] = 'admin';
            
            $this->template = $this->template = 'admin' . DS . 'categories-new.twig';
        }
    }

    /**
     * Process to new category creation
     *
     * @return void
     */
    public function newProcess(): void
    {
        $this->template = 'client' . DS . 'accueil.twig';
        
        if ($this->userToTwig['admin']) {
            $this->template = 'admin' . DS . 'categories-allready-exist.twig';

            $post = ucfirst(strtolower($this->getPOST('category')));

            $category = new CategoryModel();
            
            if (!$category->isEXist($post)) {
                $category->create($post);

                $url = SITE_ADRESS . DS . 'adminCategory';
                $this->redirect($url);
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

        $test = count($params) === 2 && $category->isExist($params[1]) && $params[1] !== 'aucune';

        if ($this->userToTwig['admin'] && $test) {
            $this->userToTwig['template'] = 'admin';

            $this->params = [
                'id'       => $params[0],
                'category' => $params[1]
            ];

            $this->template = 'admin' . DS . 'categories-update.twig';
        }
    }

    /**
     * Process to update a given category
     *
     * @param integer $categoryId
     *
     * @return void
     */
    public function updateProcess(int $categoryId): void
    {
        $this->template = 'client' . DS . 'accueil.twig';
        
        if ($this->userToTwig['admin']) {
            $this->template = 'admin' . DS . 'categories-allready-exist.twig';

            $post = ucfirst(strtolower($this->getPOST('category')));

            $category = new CategoryModel();

            if (!$category->isEXist($post)) {
                $category->update((int) $categoryId, $post);
    
                $url = SITE_ADRESS . DS . 'adminCategory';
                $this->redirect($url);
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

        $test = count($params) === 2 && $category->isExist($params[1]) && $params[1] !== 'aucune';

        if ($this->userToTwig['admin'] && $test) {
            $article = new ArticlesModel();
            $article->uncategorized((int)$params[0]);

            $category->delete((int)$params[0]);

            $url = SITE_ADRESS . DS . 'adminCategory';
            $this->redirect($url);
        }
    }
}
