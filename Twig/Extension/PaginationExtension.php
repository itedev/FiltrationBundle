<?php


namespace ITE\FiltrationBundle\Twig\Extension;

use ITE\FiltrationBundle\Filtration\Result\FiltrationResult;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class PaginationExtension
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class PaginationExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $paginationOptions = [];

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * PaginationExtension constructor.
     *
     * @param array        $paginationOptions
     * @param RequestStack $requestStack
     */
    public function __construct(array $paginationOptions, RequestStack $requestStack)
    {
        $this->paginationOptions = $paginationOptions;
        $this->requestStack      = $requestStack;
    }

    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('ite_pagination_render', [$this, 'renderPagination'], [
                'is_safe' => ['html'],
                'needs_environment' => true
            ]),
        ];
    }

    /**
     * @param FiltrationResult $result
     */
    public function renderPagination(\Twig_Environment $twig, FiltrationResult $result, $queryParams = [], $viewParams = [])
    {
        $data = [];

        $request = $this->requestStack->getMasterRequest();

        $data['route'] = $request->attributes->get('_route');
        $data['query'] = array_merge($request->query->all(), $queryParams);

        return $twig->render($this->paginationOptions['template'], array_merge(
            $this->getPaginationData($result),
            $result->getOption('pagination'),
            $viewParams,
            $data
        ));
    }

    private function getPaginationData(FiltrationResult $result)
    {
        $pageCount = $this->getPageCount($result);
        $current = $result->getOption('page');
        $pageRange = $this->paginationOptions['page_range'];
        $limit = $result->getOption('limit');
        $totalCount = $result->getCount();

        if ($pageCount < $current) {
            $result->setOption('page', $pageCount);
            $current = $pageCount;
        }

        if ($pageRange > $pageCount) {
            $pageRange = $pageCount;
        }

        $delta = ceil($pageRange / 2);

        if ($current - $delta > $pageCount - $pageRange) {
            $pages = range($pageCount - $pageRange + 1, $pageCount);
        } else {
            if ($current - $delta < 0) {
                $delta = $current;
            }

            $offset = $current - $delta;
            $pages = range($offset + 1, $offset + $pageRange);
        }

        $proximity = floor($pageRange / 2);

        $startPage  = $current - $proximity;
        $endPage    = $current + $proximity;

        if ($startPage < 1) {
            $endPage = min($endPage + (1 - $startPage), $pageCount);
            $startPage = 1;
        }

        if ($endPage > $pageCount) {
            $startPage = max($startPage - ($endPage - $pageCount), 1);
            $endPage = $pageCount;
        }

        $viewData = array(
            'last'              => $pageCount,
            'current'           => $current,
            'numItemsPerPage'   => $limit,
            'first'             => 1,
            'pageCount'         => $pageCount,
            'totalCount'        => $totalCount,
            'pageRange'         => $pageRange,
            'startPage'         => $startPage,
            'endPage'           => $endPage,
        );

        if ($current - 1 > 0) {
            $viewData['previous'] = $current - 1;
        }

        if ($current + 1 <= $pageCount) {
            $viewData['next'] = $current + 1;
        }

        $viewData['pagesInRange'] = $pages;
        $viewData['firstPageInRange'] = min($pages);
        $viewData['lastPageInRange']  = max($pages);

        if ($result->getPaginatedTarget() !== null) {
            $viewData['currentItemCount'] = count($result);
            $viewData['firstItemNumber'] = (($current - 1) * $limit) + 1;
            $viewData['lastItemNumber'] = $viewData['firstItemNumber'] + $viewData['currentItemCount'] - 1;
        }

        return $viewData;
    }

    private function getPageCount(FiltrationResult $result)
    {
        return intval(ceil($result->getCount() / $result->getOption('limit')));
    }
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'ite_filtration.twig.extension.pagination';
    }
}
