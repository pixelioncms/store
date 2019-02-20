<?php

class AdminModuleMenu extends CMenu
{


    protected function renderMenuItem($item, $count = 0)
    {

        if (isset($item['items']) && count($item['items'])) {
            $item['linkOptions'] = array(
                'data-toggle' => 'collapse',
                'aria-expanded' => 'false',
                'role' => 'button',
                'aria-controls' => 'collapse' . $count,
                'class' => 'sidebar-collapse'
            );
            $item['url'] = '#collapse' . $count;
        }
        $label = $this->linkLabelWrapper === null ? $item['label'] : Html::tag($this->linkLabelWrapper, $this->linkLabelWrapperHtmlOptions, $item['label']);
        $icon = isset($item['icon']) ? $item['icon'] : Html::icon('icon-arrow-right');

        if (isset($item['url'])) {
            $count = isset($item['count']) ? '<span class="' . ((isset($item['countClass'])) ? $item['countClass'] : 'badge badge-success') . '">' . $item['count'] . '</span>' : '';

            return Html::link($icon . '<span>' . $label . '</span>' . $count, $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : array());
        } else
            return Html::tag('span', isset($item['linkOptions']) ? $item['linkOptions'] : array(), $icon . $item['label'] . '<span class="caret"></span>');


    }

    /**
     * Recursively renders the menu items.
     * @param array $items the menu items to be rendered recursively
     */
    protected function renderMenuRecursive($items)
    {
        $count = 0;
        $n = count($items);
        foreach ($items as $item) {
            $count++;
            $options = isset($item['itemOptions']) ? $item['itemOptions'] : array();
            $class = array();
            if ($item['active'] && $this->activeCssClass != '')
                $class[] = $this->activeCssClass;
            if ($count === 1 && $this->firstItemCssClass !== null)
                $class[] = $this->firstItemCssClass;
            if ($count === $n && $this->lastItemCssClass !== null)
                $class[] = $this->lastItemCssClass;
            if ($this->itemCssClass !== null)
                $class[] = $this->itemCssClass;
            if ($class !== array()) {
                if (empty($options['class']))
                    $options['class'] = implode(' ', $class);
                else
                    $options['class'] .= ' ' . implode(' ', $class);
            }


            echo CHtml::openTag('li', $options);

            $menu = $this->renderMenuItem($item, $count);
            if (isset($this->itemTemplate) || isset($item['template'])) {
                $template = isset($item['template']) ? $item['template'] : $this->itemTemplate;
                echo strtr($template, array('{menu}' => $menu));
            } else
                echo $menu;

            // $item['submenuOptions']=array('class'=>'dropdown-menu');

            if (isset($item['items']) && count($item['items'])) {
                echo '<div class="collapse" id="collapse' . $count . '">';
                echo "\n" . CHtml::openTag('ul', isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions) . "\n";
                $this->renderMenuRecursive($item['items']);
                echo CHtml::closeTag('ul') . "\n";
                echo '</div>';
            }

            echo CHtml::closeTag('li') . "\n";
        }
    }
}
