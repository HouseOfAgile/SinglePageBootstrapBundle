<?php


namespace HOA\Bundle\SinglePageBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\MenuFactory;
use Symfony\Component\HttpFoundation\Request;
use Dacorp\ExtraBundle\Menu\MenuBuilder;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Translation\TranslatorInterface;
use JMS\TranslationBundle\Annotation\Ignore;



class FrontendMenuBuilder
{
    /**
     * Menu factory.
     *
     * @var FactoryInterface
     */
    protected $factory;
    /**
     * Translator instance.
     *
     * @var TranslatorInterface
     */
    protected $translator;

    protected $defaultLang;

    protected $availableLangs;

    /* @var $menuBuilder MenuBuilder */
    protected $menuBuilder;

    /**
     * Request.
     *
     * @var Request
     */
    protected $request;

    public function __construct( MenuFactory $menuFactory,TranslatorInterface $translator, MenuBuilder $menuBuilder, $defaultLang, $availableLangs)
    {
        $this->factory = $menuFactory;
        $this->translator = $translator;
        $this->defaultLang = $defaultLang;
        $this->availableLangs = $availableLangs;
        $this->menuBuilder = $menuBuilder;
    }

    public function createLangMenu()
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav navbar-right'
            )
        ));


        $languageDropDown = $menu->addChild('lang', array(
            'caret' => false,
            'dropdown' => true,
            'class' => ' navbar-left',
            'extras' => array('safe_label' => true)
        ));

        $currLang=$this->request->getSession()->get('_locale', $this->defaultLang);
        $languageDropDown->setLabel('<i class="fa fa-flag"></i> '.$currLang.' <i class="fa fa-chevron-down"></i>');
        //create the childs
        foreach ($this->availableLangs as $ilang) {
            if ($ilang != $currLang) {
                $languageDropDown->addChild($ilang, array(
                    'route' => 'change_lang',
                    'extras' => array(
                        'icon' => 'flag',
                    ),
                    'routeParameters' => array('newlang' => $ilang)));
            }
        }
        return $menu;
    }


    /**
     * Sets the request the service
     *
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }
}
