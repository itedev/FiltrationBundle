<?php


namespace ITE\FiltrationBundle\Filtration\Builder;

use ITE\FiltrationBundle\Filtration\Filter\CompiledFilter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class FilterBuilder
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FilterBuilder
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FormBuilderInterface
     */
    private $formBuilder = null;

    /**
     * @var string
     */
    private $targetName;

    /**
     * @var bool
     */
    private $started = false;

    /**
     * @var bool
     */
    private $ended = false;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param $name
     * @param $targetName
     */
    public function start($name, $targetName)
    {
        $this->formBuilder = $this->formFactory->createNamedBuilder($name);
        $this->started     = true;
        $this->targetName  = $targetName;
    }

    /**
     * @param       $name
     * @param       $type
     * @param array $options
     */
    public function add($name, $type, $options = [])
    {
        if (!$this->started) {

        }
        $this->formBuilder->add($name, $type, $options);
    }

    public function end()
    {
        $this->ended = true;
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function getForm()
    {
        if (!$this->started) {
            throw new \LogicException('You need to start builder first.');
        }

        if (!$this->ended) {
            throw new \LogicException('You need to end builder first.');
        }

        $this->started = false;
        $this->ended   = false;

        return $this->formBuilder->getForm();
    }


    public function getFilter()
    {
        $form = $this->getForm();
        return new CompiledFilter($form, $this->targetName);
    }
}