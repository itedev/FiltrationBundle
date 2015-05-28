<?php


namespace ITE\FiltrationBundle\Twig\TokenParser;

use ITE\FiltrationBundle\Filtration\FiltrationManager;
use ITE\FiltrationBundle\Twig\Node\TwigNodeFilterEmbed;

/**
 * Class FilterEmbedTokenParser
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FilterEmbedTokenParser extends \Twig_TokenParser_Include
{
    /**
     * @var FiltrationManager
     */
    private $filtrator;

    public function __construct(FiltrationManager $filtrator)
    {
        $this->filtrator = $filtrator;
    }

    /**
     * Parses a token and returns a node.
     *
     * @param \Twig_Token $token A Twig_Token instance
     *
     * @return \Twig_NodeInterface A Twig_NodeInterface instance
     */
    public function parse(\Twig_Token $token)
    {
        $stream = $this->parser->getStream();

        $parent       = $this->parser->getExpressionParser()->parseExpression();
        $filterName   = $parent->getAttribute('value');
        $filter       = $this->filtrator->getFilter($filterName);
        $templateName = $this->filtrator->getFilter($parent->getAttribute('value'))->getTemplateName();
        $parent       = new \Twig_Node_Expression_Constant($templateName, $parent->getLine());

        list($variables, $only, $ignoreMissing) = $this->parseArguments();
        /** @var \Twig_Node_Expression_Array $variables */
        if ($variables === null) {
            $variables = new \Twig_Node_Expression_Array([], $parent->getLine());
        }
        $variables->addElement(
            new \Twig_Node_Expression_Array(
                $this->filtrator->getFilterForm($filterName)->createView(),
                $variables->getLine()
            ),
            new \Twig_Node_Expression_Constant('form', $variables->getLine())
        );
        $variables->addElement(
            new \Twig_Node_Expression_Array(
                $filter,
                $variables->getLine()
            ),
            new \Twig_Node_Expression_Constant('filter', $variables->getLine())
        );


        // inject a fake parent to make the parent() function work
        $stream->injectTokens(
            array(
                new \Twig_Token(\Twig_Token::BLOCK_START_TYPE, '', $token->getLine()),
                new \Twig_Token(\Twig_Token::NAME_TYPE, 'extends', $token->getLine()),
                new \Twig_Token(\Twig_Token::STRING_TYPE, '__parent__', $token->getLine()),
                new \Twig_Token(\Twig_Token::BLOCK_END_TYPE, '', $token->getLine()),
            )
        );

        $module = $this->parser->parse($stream, array($this, 'decideBlockEnd'), true);

        // override the parent with the correct one
        $module->setNode('parent', $parent);

        $this->parser->embedTemplate($module);

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new TwigNodeFilterEmbed(
            $module->getAttribute('filename'),
            $module->getAttribute('index'),
            $variables,
            $only,
            $ignoreMissing,
            $token->getLine(),
            $this->getTag()
        );
    }

    public function decideBlockEnd(\Twig_Token $token)
    {
        return $token->test('end_filter_embed');
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'filter_embed';
    }
}