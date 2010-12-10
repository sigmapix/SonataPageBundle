<?php
/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Bundle\PageBundle\Block;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * PageExtension
 *
 *
 * @author     Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class ActionBlockService extends BaseBlockService
{
    public function execute($block)
    {

        return $this->render($block->getTemplate(), array(
             'block' => $block
        ));
    }

    public function validateBlock($block)
    {
        // TODO: Implement validateBlock() method.
    }

    public function defineBlockGroupField($field_group, $block)
    {
        $field_group->add(new \Symfony\Component\Form\TextField('action'));

        $parameters = new \Symfony\Component\Form\FieldGroup('parameters');
        
        foreach($block->getSetting('parameters') as $name => $value) {
            $parameters->add(new \Symfony\Component\Form\TextField($name));
        }

        $field_group->add($parameters);
    }

}