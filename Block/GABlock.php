<?php
/**
 * Created by PhpStorm.
 * User: ersah
 * Date: 4/24/17
 * Time: 3:58 PM
 */

namespace Ersah\GABundle\Block;

use Ersah\GABundle\Utils\GA;
use Ersah\GABundle\Utils\GAMain;
use Knp\Bundle\MenuBundle\Tests\Stubs\ContainerAwareBundleInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;

class GABlock extends AbstractBlockService
{
    /** @var GAMain  */
    private $ga;

    /**
     * GABlock constructor.
     * @param null|string $name
     * @param \Sonata\BlockBundle\Block\Service\EngineInterface|\Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param GA $ga
     */
    public function __construct($name, $templating, $ga)
    {
        parent::__construct($name, $templating);
        $this->ga = $ga;
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'url'      => false,
            'title'    => 'Insert the rss title',
            'template' => 'ErsahGABundle:Block:GA.html.twig',
        ));
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper
            ->add('settings', 'sonata_type_immutable_array', array(
                'keys' => array(
                    array('url', 'url', array('required' => false)),
                    array('title', 'text', array('required' => false)),
                )
            ))
        ;
    }
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings.url')
                ->assertNotNull(array())
                ->assertNotBlank()
            ->end()
            ->with('settings.title')
                ->assertNotNull(array())
                ->assertNotBlank()
                ->assertMaxLength(array('limit' => 50))
            ->end()
        ;
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();
        $report = $this->ga->getMainReport();

        return $this->renderResponse('ErsahGABundle:Block:GA.html.twig', array(
            'report' => $report,
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ), $response);
    }

}