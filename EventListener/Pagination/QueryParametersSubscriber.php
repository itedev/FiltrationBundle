<?php


namespace ITE\FiltrationBundle\EventListener\Pagination;

use ITE\FiltrationBundle\Event\PaginationEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class QueryParametersSubscriber
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class QueryParametersSubscriber
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var array
     */
    private $defaultParameterNames;

    /**
     * @param RequestStack $requestStack
     * @param              $defaultParameterNames
     */
    public function __construct(RequestStack $requestStack, $defaultParameterNames = [])
    {
        $this->requestStack          = $requestStack;
        $this->defaultParameterNames = $defaultParameterNames;
    }

    public function paginate(PaginationEvent $event)
    {
        $page = $event->getOptions()->get('page');

        if (null !== $page) {
            return;
        }

        $pageParameterName = $event->getOptions()->get('page_parameter_name');

        if (null === $pageParameterName) {
            $pageParameterName = isset($this->defaultParameterNames['page']) ? $this->defaultParameterNames['page']: 'page';
        }

        $page = $this->requestStack->getMasterRequest()->request->get($pageParameterName, 1);

        $event->getOptions()->set('page', $page);
    }
}