<?php


namespace ITE\FiltrationBundle\Util;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Interface UrlGeneratorInterface
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
interface UrlGeneratorInterface
{
    /** @todo maybe we should make this constant configurable? */
    const SORT_FIELD_PREFIX = '_sort_';

    /**
     * Returns url with reset filter for given form field
     *
     * @param FormInterface|FormView $form
     * @return string
     */
    public function getResetFilterFieldUrl($form);

    /**
     * Returns url with reset filter for given filter form
     *
     * @param FormInterface|FormView $form
     * @return string
     */
    public function getResetFilterUrl($form);

    /**
     * Returns sort url for given form field and direction
     *
     * @param FormInterface|FormView $form
     * @param  string                $direction
     * @return string
     */
    public function getSortFieldUrl($form, $direction);

    /**
     * Returns reset sort url for given form field
     *
     * @param FormInterface|FormView $form
     * @return string
     */
    public function getResetSortFieldUrl($form);

    /**
     * Returns reset sort url for given filter form
     *
     * @param FormInterface|FormView $form
     * @return string
     */
    public function getResetSortUrl($form);
}