<?php

//Yii::import('web.widgets.CWidget');

/**
 * Display vertical tabs in sidebar
 */
class AdminTabs extends CWidget
{

    public $tabs = array();
    public $positionTabs;
    public $errorSummary = false;

    public function run()
    {
        $positon = ($this->positionTabs == 'vertical') ? 'vertical' : 'static';
        $liContent = '';
        $tabContent = '';
        $cs = Yii::app()->getClientScript();


        $cs->registerScript('tabs-admin', "
            var xhr_tabs;
            var url = document.location.toString();
            var position = '" . $positon . "';
            var hashID = '" . $this->getRedirectTabsHash() . "';

            $('#tabs-form a').on('show.bs.tab', function (e) {
                console.log($(this).data('target'));
                if(e.target.hash !== ''){
                    $.ajax({
                        type:'POST',
                        url:'/admin/ajax/setHashstate',
                        data:{hash:e.target.hash.replace('#','')}  
                    });
                    console.log(e.target.hash);
                    window.location.hash = e.target.hash;
                }

            });
            $('#tabs-form a').on('shown.bs.tab', function (e) {
                if(position === 'vertical'){
                    var height = $(this).parent().parent().height();
                    $('.tab-content').find('.tabs-pan-vertical.active').css({'min-height':height});
                }


            });
            if (url.match('#')) {
                $('#tabs-form a[href=\"' + location.hash + '\"]').tab('show');
            }else{
                $('#tabs-form a:first').tab('show');
            }

            $('#tabs-form a').click(function (e) {
                e.preventDefault();
                var that = $(this);
                var url = that.attr('href');
                var target = that.data('target');
                $(this).tab('show');  
                if (!url.match('#')) {
                    if($(target).text() === ''){
                    
                        if(xhr_tabs && xhr_tabs.readyState != 4){
                            xhr_tabs.onreadystatechange = null;
                            xhr_tabs.abort();
                        }
                        xhr_tabs = $.ajax({
                            type:'GET',
                            url:url,
                            //data:{hash:e.target.hash.replace('#','')},
                            success:function(data){
                                $(target).html(data);
                            }
                        });
                    }
                }
   

            });", CClientScript::POS_END);

        $n = 0;

        // $tabContent .= $this->errorSummary;
        echo $this->errorSummary;
        foreach ($this->tabs as $title => $content) {
            $id = (isset($content['id']))?$content['id']:'tab_' . $n;
            if (isset($content['ajax'])) {
                //$tabContent .= $content;
                $tabContent .= CHtml::openTag('div', array(
                    'id' => $id,
                    'class' => "tab-pane tabs-pan-{$positon}",
                ));

                $tabContent .= CHtml::closeTag('div');
                $title = (preg_match('#^(icon-)#ui', $title)) ? '<i class="' . $title . '"></i>' : $title;
                $liContent .= '<li class="flex-sm-fill text-center nav-item"><a class="nav-link" data-target="#' . $id . '" href="' . $content['ajax'] . '">' . $title . '</a></li>';
            } else {
                $tabContent .= CHtml::openTag('div', array(
                    'id' => $id,
                    'class' => "tab-pane tabs-pan-{$positon}",
                ));
                $tabContent .= (is_array($content)) ? $content['content'] : $content;


                $tabContent .= CHtml::closeTag('div');
                $title = (preg_match('#^(icon-)#ui', $title)) ? '<i class="' . $title . '"></i>' : $title;
                $liContent .= '<li class="flex-sm-fill text-center nav-item"><a class="nav-link" data-target="#' . $id . '" href="#' . $id . '">' . $title . '</a></li>';
            }
            $n++;
        }

        echo CHtml::openTag('div', array('class' => 'clearfix tab-content'));// tab-content
        echo '<ul class="nav nav-tabs nav-pills flex-column flex-sm-row nav-tabs-' . $positon . '" id="tabs-form">' . $liContent . '</ul>' . $tabContent;
        echo CHtml::closeTag('div');
    }

    /**
     * @param boolean $value whether the user is a superuser.
     */
    public function setRedirectTabsHash($value)
    {
        Yii::app()->user->setState('redirectTabsHash', $value);
    }

    /**
     * @return boolean whether the user is a superuser.
     */
    public function getRedirectTabsHash()
    {
        return Yii::app()->user->getState('redirectTabsHash');
    }
}
