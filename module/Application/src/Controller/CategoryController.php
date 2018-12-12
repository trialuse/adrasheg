<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\Category;
use User\Form\CategoryForm;

/**
 * This is the main controller class of the User Demo application. It contains
 * site-wide actions such as Home or About.
 */
class CategoryController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     */
    public function __construct($entityManager, $categoryManager)
    {
       $this->entityManager = $entityManager;
       $this->categoryManager = $categoryManager;
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * list of categories.
     */
    public function indexAction()
    {
        $categories = $this->entityManager->getRepository(Category::class)
            ->findBy([], ['id'=>'ASC']);

        return new ViewModel([
            'categories' => $categories
        ]);
    }

    /**
     * This action displays a page allowing to add a new permission.
     */
    public function addAction()
    {
        // Create form
        $form = new CategoryForm($this->entityManager);

        // Check if user has submitted the form
        if($this->getRequest()->isPost()) {

            // Make certain to merge the files info!
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            // Pass data to form
            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Move uploaded file to its destination directory.
                $data = $form->getData();
                $data['image'] = $data['file']['name'];

                $this->categoryManager->add($data);

                return $this->redirect()->toRoute('category');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * This action deletes.
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $category = $this->entityManager->getRepository(Category::class)
            ->find($id);

        if ($category == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Delete permission.
        $this->categoryManager->delete($category);

        // Redirect to "index" page
        return $this->redirect()->toRoute('category');
    }

    /**
     * This is the default main action.
     */
    public function mainAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if (!$this->access('user.manage')) {
            return $this->redirect()->toRoute('not-authorized');
        }

        // Find a guest with such ID.
        $category = $this->entityManager->getRepository(Category::class)
            ->find($id);

        $this->categoryManager->main($category);

        return $this->redirect()->toRoute('category');
    }
}

