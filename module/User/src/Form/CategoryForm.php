<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;

/**
 * This form is used to collect user's email, full name, password and status. The form
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, user
 * enters password, in 'update' scenario he/she doesn't enter password.
 */
class CategoryForm extends Form
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Constructor.
     */
    public function __construct($entityManager = null)
    {
        // Define form name
        parent::__construct('category-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Set binary content encoding
        $this->setAttribute('enctype', 'multipart/form-data');

        // Save parameters for internal use.
        $this->entityManager = $entityManager;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        // Add "email" field
        $this->add([
            'type'  => 'select',
            'name' => 'category',
            'options' => [
                'label' => 'Category',
                'value_options' => array(
                    '1' => 'Category1',
                    '2' => 'Category2',
                    '3' => 'Category3',
                    '4' => 'Category4',
                ),
            ],
        ]);

        // Add "email" field
        $this->add([
            'type'  => 'select',
            'name' => 'is_main',
            'options' => [
                'label' => 'Main?',
                'value_options' => array(
                    '1' => 'Main',
                    '2' => 'Not'
                ),
            ],
        ]);

        // Add "file" field
        $this->add([
            'type'  => 'file',
            'name' => 'file',
            'attributes' => [
                'id' => 'file'
            ],
            'options' => [
                'label' => 'Image file',
            ],
        ]);

        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Upload',
                'id' => 'submitbutton',
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Add input for "category" field
        $inputFilter->add([
            'name'     => 'category',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
        ]);

        // Add input for "is_main" field
        $inputFilter->add([
            'name'     => 'is_main',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
        ]);

        // Add validation rules for the "file" field
        $inputFilter->add([
            'type'     => FileInput::class,
            'name'     => 'file',
            'required' => true,
            'validators' => [
                ['name'    => 'FileUploadFile'],
                [
                    'name'    => 'FileMimeType',
                    'options' => [
                        'mimeType'  => ['image/jpeg', 'image/png']
                    ]
                ],
                ['name'    => 'FileIsImage'],
                [
                    'name'    => 'FileImageSize',
                    'options' => [
                        'minWidth'  => 128,
                        'minHeight' => 128,
                        'maxWidth'  => 4096,
                        'maxHeight' => 4096
                    ]
                ],
            ],
            'filters'  => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target'=>'./public/upload/images',
                        'useUploadName'=>true,
                        'useUploadExtension'=>true,
                        'overwrite'=>true,
                        'randomize'=>false
                    ]
                ]
            ],
        ]);
    }
}