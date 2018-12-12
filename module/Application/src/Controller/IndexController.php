<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\Category;
use User\Form\OrderForm;
use Application\Controller\MailController;


require './vendor/mailer/phpMailer.php';
require './vendor/mailer/smtp.php';

/**
 * This is the main controller class of the User Demo application. It contains
 * site-wide actions such as Home or About.
 */
class IndexController extends AbstractActionController 
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $orderManager;

    private $categoryManager;
    
    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     */
    public function __construct($entityManager, $orderManager, $categoryManager)
    {
       $this->entityManager = $entityManager;
       $this->orderManager = $orderManager;
       $this->categoryManager = $categoryManager;
    }
    
    /**
     * This is the default "index" action of the controller. It displays the 
     * Home page.
     */
    public function indexAction() 
    {
        // Create the form model
        $form = new OrderForm();

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

                $order = $this->orderManager->add($data);

                return $this->redirect()->toRoute('application', ['action'=>'thanks']);
//$email = $order->getEmail();

//                return $this->redirect()->toRoute('mail', ['action'=>'index', 'email'=>$order->getEmail()]);

//                $mail = new \PHPMailer();
//                $mail->IsSMTP();
//                $mail->CharSet = 'UTF-8';
//
//                $mail->Host       = "smtp.adrasheg.am"; // SMTP server example
//                $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
//                $mail->SMTPAuth   = true;                  // enable SMTP authentication
//                $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
//                $mail->Username   = "adrashegam"; // SMTP account username example
//                $mail->Password   = "Adrasheg1957*";        // SMTP account password example
//
//                $mail->From = "info@adrasheg.am";
//                $mail->FromName = "adrasheg";
//                //To address and name
//                $mail->addAddress("grtumanyan@gmail.com", "phpmailer test");
//                $mail->Subject = "New Booking";
//                $mail->Body = "<i>From: ".$email."</i>";
////                if($mail->send())
////                {
////                    // Redirect the user to "thanks" page
////                    return $this->redirect()->toRoute('application', ['action'=>'thanks']);
////                }
//                if(!$mail->send())
//                {
//                    var_dump("Mailer Error: " . $mail->ErrorInfo);
//                }
//                else
//                {
//                    echo "Message has been sent successfully";
//                }
            }
        }

        // Render the page
        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * The "works" action displays the info about works.
     */
    public function worksAction()
    {

        $works = $this->entityManager->getRepository(Category::class)
            ->findBy([], ['id'=>'ASC']);

        return new ViewModel([
            'works' => $works
        ]);
    }

    /**
     * This is the default "admin" action of the controller.
     */
    public function adminAction()
    {

        if (!$this->access('user.manage')) {
            return $this->redirect()->toRoute('not-authorized');
        }

        return $this->redirect()->toRoute('category');
    }

    /**
     * This is the default "thanks" action of the controller.
     */
    public function thanksAction()
    {
        return new ViewModel();
    }
}

