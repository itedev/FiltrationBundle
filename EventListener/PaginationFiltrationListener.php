<?php


namespace ITE\FiltrationBundle\EventListener;


use ITE\FiltrationBundle\Event\FiltrationEvent;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class PaginationFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 * @todo WIP, will create service after development done.
 */
class PaginationFiltrationListener extends AbstractFiltrationListener
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack            $requestStack
     * @param null|PaginatorInterface $paginator
     */
    function __construct(RequestStack $requestStack, $paginator = null)
    {
        $this->paginator    = $paginator;
        $this->requestStack = $requestStack;
    }


    /**
     * @param FiltrationEvent $event
     */
    public function filter(FiltrationEvent $event)
    {
        $options = $event->getOptions();

        if (!isset($options['paginate']) || true !== $options['paginate']) {
            return;
        }

        if (!$this->paginator) {
            throw new \InvalidArgumentException('You need to install knp_pagintor for use pagination option.');
        }

        $target = $this->paginator->paginate(
            $event->getTarget(),
            $this->requestStack->getMasterRequest()->query->get($options['pagination_options']['query_param']),
            $options['pagination_options']['limit'],
            $options['pagination_options']['options']
        );
        $event->setTarget($target);
    }

}