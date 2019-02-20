<?php

/**
 * Render form using jquery tabs.
 * @package Widgets
 */
class TabForm extends CMSForm
{

    /**
     * @var array list of tabs (tab title=>tab content). Will be
     * generated from form elements.
     */
    protected $tabs = array();

    /**
     * @var array Additional tabs to render.
     */
    public $additionalTabs = array();

    /**
     * @var string Widget to render form. zii.widgets.jui.CJuiTabs
     */
    public $formWidget = 'mod.admin.widgets.AdminTabs';
    protected $activeTab = null;
    public $positionTabs = null; //vertical or null
    public $hasError = false;

    public function render()
    {
        if ($this->positionTabs == 'vertical') {
            $cs = Yii::app()->getClientScript();
            $cs->registerScript('tabs-nav-vertical', '

                $.fn.stickyfloat = function (options, lockBottom) {
                    if ($(window).width() >= 768) {
                        var $obj = this;
                        var parentPaddingTop = parseInt($obj.parent().css("top"));
                        var startOffset = $obj.parent().offset().top;
                        var opts = $.extend({startTop: 10, startOffset: startOffset, offsetY: parentPaddingTop, duration: 500, lockBottom: true}, options);

                       // $obj.css({top: opts.startTop});

                        if (opts.lockBottom) {
                            var bottomPos = $obj.parent().height() - $obj.height() + parentPaddingTop;
                            if (bottomPos < 0)
                                bottomPos = 0;
                        }

                        $(window).scroll(function () {
                            $obj.stop();
                            var testVar = 50;
                            var pastStartOffset = $(document).scrollTop() > opts.startOffset;
                            var objFartherThanTopPos = $obj.offset().top > startOffset;
                            var objBiggerThanWindow = $obj.outerHeight() < $(window).height();

                            if ((pastStartOffset || objFartherThanTopPos) && objBiggerThanWindow) {
                                var newpos = ($(document).scrollTop() - startOffset + opts.offsetY);
                                if (newpos > bottomPos)
                                    newpos = bottomPos;
                                if ($(document).scrollTop() < opts.startOffset)
                                    newpos = parentPaddingTop;
   
                                var prnt = $obj.parent(".tab-content").height();
                                var me = $obj.height();
                                if ((newpos + testVar + me) >= prnt) {
                                    $(".nav-tabs-vertical > li:first-child a").css({"border-top":"1px solid #cdcdcd"});
                                    $obj.animate({top: -1}, opts.duration); //me - 
                                    console.log("1");
                                } else {
                                    if ((newpos + testVar) < opts.startTop) {
                                        $obj.animate({top: opts.startTop}, opts.duration);
                                        console.log("2");
                                    } else {
                                    console.log("3");
                                        if(newpos === 0){
                                            $(".nav-tabs-vertical > li:first-child a").css({"border-top":"1px solid #fff"});
                                        }else{
                                            $(".nav-tabs-vertical > li:first-child a").css({"border-top":"1px solid #cdcdcd"});
                                        }
                                        $obj.animate({top: (newpos === 0)?opts.startTop:newpos-testVar}, opts.duration);

                                    }
                                }
                            }
                        });
                    }
                };
                $(document).ready(function ($) {
                    if ($("#tabs-form").length > 0) {
                        if ($(window).width() <= 768) {
                            $("#tabs-form").css({"position": "static"});
                            $("#' . $this->id . ' .tab-pane").removeClass("tabs-pan-vertical");
                        } else {
                            $("#tabs-form").stickyfloat({duration: 500, startTop: 0});
                        }
                    }
                });


                function resizeTabs(){
                    if ($(window).width() >= 768) {
                        var widgetWidth = $("#' . $this->id . '").parent(".panel-container").width()-15-2;
                        var tabsWidth = $("#' . $this->id . '").find(".nav-tabs").width();
                        $("#' . $this->id . ' .tab-pane").css({"width":widgetWidth - tabsWidth});
                    }
                }

                resizeTabs();
                $(window).resize(function(){
                 if ($(window).width() >= 768) {
                    resizeTabs();
                    }
                });
                 if ($(window).width() >= 768) {
                $("#menu-toggle").click(function(){
                   var tabsWidth = $("#' . $this->id . '").find(".nav-tabs").width();
                    if($("#wrapper").hasClass("active")){
                        var widgetWidth = $(".card-body").width()-200;
                    }else{
                        var widgetWidth = $(".card-body").width()+200;
                    }
                 // $("#' . $this->id . ' .tab-pane").animate({width: widgetWidth - tabsWidth}, 300);
                   $("#' . $this->id . ' .tab-pane").css({"width":widgetWidth - tabsWidth});


                });
                }

            ', CClientScript::POS_READY);
        }
        $result = $this->renderBegin();
        $result .= $this->renderElements();
        $result .= $this->renderEnd();
        return $result;
    }

    public function tabs()
    {
        $this->render();
        $result = $this->renderBegin();
        if ($this->showErrorSummary && ($model = $this->getModel(false)) !== null) {
            // Display errors summary on each tab.
            $errorSummary = $this->getActiveFormWidget()->errorSummary($model, null, null, array('class' => CHtml::$errorSummaryCss . ' alert alert-danger')) . "\n";
            //$result = $errorSummary . $result;
        } else {
            $errorSummary = false;

        }
        $behavior = $this->getModel()->behaviors();

        if (isset($behavior['seo'])) {
            $this->additionalTabs[Yii::t('app', 'TAB_META')] = array(
                'content' => Yii::app()->controller->renderPartial('mod.seo.views.admin.default._module_seo', array('model' => $this->model, 'form' => $this), true)
            );
        }
        //@todo conflict with duplicated widget
        /*if (isset($this->getModel()->enableAttachment) && $this->getModel()->enableAttachment) {
            $this->additionalTabs['Изображение'] = array(
                'content' => Yii::app()->controller->widget('ext.attachment.AttachmentWidget', array(
                    'model' => $this->getModel(),
                    'skin' => ($this->positionTabs == 'vertical') ? 'default_fullwidth' : 'default_fullwidth'
                        ), true),
            );
        }*/
        $result .= $this->owner->widget($this->formWidget, array(
            'tabs' => CMap::mergeArray($this->tabs, $this->additionalTabs),
            'positionTabs' => $this->positionTabs,
            'errorSummary' => $errorSummary
        ), true);
        $result .= $this->renderButtons();
        $result .= $this->renderEnd();
        return $result;
    }

    /**
     * Renders elements
     * @return string
     */
    public function renderElements()
    {
        $output = '';
        foreach ($this->getElements() as $element) {

            if ($element->visible === true) {
                if (isset($element->title))
                    $this->activeTab = $element->title;

                $out = '' . $this->renderElement($element) . '';
                $this->tabs[$this->activeTab] = $out;
                $output .= $out;
            }

        }
        return $output;
    }

}
