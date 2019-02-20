<div id="topcontrol">
    <?php if($this->enableTop){ ?><div class="backtotop hexagon" <?php echo ( ($this->opacity >0 && $this->opacity <= 1) ? '' : 'style="display:none;"'); ?>><i class="hexagon-arrow-up"></i><span></span><small>Up</small></div><?php } ?>
    <?php if($this->enableBottom){ ?><div class="backtobottom" <?php echo ( ($this->opacity >0 && $this->opacity <= 1) ? '' : 'style="display:none;"'); ?>><i class="icon-arrow-down"></i></div><?php } ?>
</div>

    <?php
Yii::app()->clientScript->registerScript('topBottomScroll-js', "

    $(document).ready(function() {
        " . ( ($this->opacity >0 && $this->opacity <= 1)?"fadeOutTo(); showFadeOnMouseScrollButtons()":"$(window).scroll(function(){showHideScrollButtons()});" ) . "
    });
    
    " . ( ($this->opacity >0 && $this->opacity <= 1)?"":"showHideScrollButtons();" ) . "

    " . ( ($this->opacity >0 && $this->opacity <= 1)?"
        function fadeOutTo(){
            $('.backtotop').fadeTo(" . $this->fadeInTime . ", " . $this->opacity . ");
            $('.backtobottom').fadeTo(" . $this->fadeInTime . ", " . $this->opacity . ");
        }

        function showFadeOnMouseScrollButtons(){
            $('.backtotop').mouseover(function(){
                $('.backtotop').fadeTo(" . $this->fadeInTime . ", 1);
            });
            $('.backtotop').mouseout(function(){
                $('.backtotop').fadeTo(" . $this->fadeInTime . ", " . $this->opacity . ");
            });

            $('.backtobottom').mouseover(function(){
                $('.backtobottom').fadeTo(" . $this->fadeInTime . ", 1);
            });
            $('.backtobottom').mouseout(function(){
                $('.backtobottom').fadeTo(" . $this->fadeInTime . ", " . $this->opacity . ");
            });
        } 
    ":"
        function showHideScrollButtons(){
            if ($(this).scrollTop() > " . $this->minHeight . ") {
                $('.backtotop').fadeIn(" . $this->fadeInTime . ");
            } else {
                $('.backtotop').fadeOut(" . $this->fadeOutTime . ");
            }

            if (($(document).height() - $(this).scrollTop()) > " . $this->minDepth . ") {
                $('.backtobottom').fadeIn(" . $this->fadeInTime . ");
            } else {
                $('.backtobottom').fadeOut(" . $this->fadeOutTime . ");
            }
        }
    " ) . "

    
    $('.backtotop').click(function() {
        $(\"html, body\").animate({ scrollTop: 0 }, " . $this->scrollTopTime . ");
        return false;
    });

    $('.backtobottom').click(function() {
        $(\"html, body\").animate({ scrollTop: $(document).height() },  " . $this->scrollBottomTime . ");
        return false;
    });

");

Yii::app()->clientScript->registerCss('topBottomScroll-css', " 
    #topcontrol{
        position: fixed;
        bottom: 50px;
        right: 50px;
        opacity: 1;
        cursor: pointer;
        display:none;
    }
    @media (min-width: 992px) {
        #topcontrol{
            display:block
        }
    }
");

