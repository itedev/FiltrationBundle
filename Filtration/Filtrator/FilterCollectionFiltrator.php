<?php


namespace ITE\FiltrationBundle\Filtration\Filtrator;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use ITE\FiltrationBundle\Filtration\FiltrationAwareInterface;
use ITE\FiltrationBundle\Filtration\FiltrationManager;
use Symfony\Component\Form\FormInterface;

/**
 * Class FilterCollectionFiltrator
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FilterCollectionFiltrator extends AbstractFiltrator implements FiltrationAwareInterface
{
    /**
     * @var FiltrationManager
     */
    private $filtrationManager;
    /**
     * @param FiltrationManager $filtrationManager
     * @return mixed
     */
    public function setFiltrationManager(FiltrationManager $filtrationManager)
    {
        $this->filtrationManager = $filtrationManager;
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    public function supports(FormInterface $form)
    {
        return $this->supportsType($form, 'filter_collection');
    }

    /**
     * @inheritdoc
     */
    public function filter($target, FormInterface $form, $fieldName = null)
    {
        $fieldName = $fieldName ?: $form->getName();

        /** @var FormInterface $child */
        foreach ($form as $child) {
            foreach ($this->filtrationManager->getFiltrators() as $filtrator) {
                if ($filtrator->supports($child)) {
                    $target = $filtrator->filter($target, $child, $fieldName);
                }
            }
        }

        return $target;
    }

}