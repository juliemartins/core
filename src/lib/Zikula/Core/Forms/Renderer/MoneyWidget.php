<?php

namespace Zikula\Core\Forms\Renderer;

use Symfony\Component\Form\FormView;
use Zikula\Core\Forms\RendererInterface;
use Zikula\Core\Forms\FormRenderer;

/**
 *
 */
class MoneyWidget implements RendererInterface
{
    public function getName()
    {
        return 'money_widget';
    }
    
    public function render(FormView $form, $variables, FormRenderer $renderer)
    {
        return str_replace('{{ widget }}', 
                           $renderer->getRender('field_widget')->render($form, $variables, $renderer), 
                           $variables['money_pattern']);
    }
}
