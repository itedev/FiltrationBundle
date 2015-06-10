<?php


namespace ITE\FiltrationBundle\Twig\Extension;

use ITE\FiltrationBundle\Sorting\UrlGenerator;
use Symfony\Component\Form\FormView;

/**
 * Class SortingExtension
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class SortingExtension extends \Twig_Extension
{
    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @param UrlGenerator $urlGenerator
     */
    function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }


    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('ite_filtration_sort_button', [$this, 'sortButton'], [
                'pre_escape' => 'html',
                'is_safe' => ['html'],
                'needs_environment' => true
            ]),
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @param FormView          $form
     * @param string            $direction
     * @param array             $options
     *
     * @return string
     */
    public function sortButton(\Twig_Environment $twig, FormView $form, $direction, $options = [])
    {
        $template = $twig->resolveTemplate('@ITEFiltrationBundle/Resources/views/Helpers/sorting_buttons.html.twig');

        return $template->renderBlock('sort_link', [
            'form' => $form,
            'direction' => $direction,
            'options' => $options,
            'url' => $this->urlGenerator->generateFormViewSortingUrl($form, $direction),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ite_filtration.twig.extension.sorting';
    }

} 