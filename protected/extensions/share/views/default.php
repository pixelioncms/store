<div class="social-share">
    <h6>Поделится:</h6>
    <ul>
        <li class="facebook_share">
            <a href="javascript:void(0);" title="Share on Facebook"
               onclick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?= $this->title ?>&amp;p[summary]=&amp;p[url]=<?= Yii::app()->createAbsoluteUrl($this->model->getUrl()) ?>&amp;p[images][0]=<?= Yii::app()->createAbsoluteUrl($this->image) ?>', 'sharer', 'toolbar=0,status=0,width=620,height=280');"><i
                        class="icon-facebook"></i>
            </a>
        </li>
        <li class="facebook_share">
            <a href="javascript:void(0);" title="Share on Facebook"
               onclick="window.open('http://www.facebook.com/sharer.php', 'sharer', 'toolbar=0,status=0,width=620,height=280');"><i
                        class="icon-facebook"></i>
            </a>
        </li>
        <li class="twitter_share">
            <a href="javascript:void(0);" title="Share on Twitter"
               onclick="popUp = window.open('http://twitter.com/home?status=<?= Yii::app()->createAbsoluteUrl($this->model->getUrl()) ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false;"><i
                        class="icon-twitter"></i>
            </a>
        </li>
        <li class="">
            <a href="javascript:void(0);" title="Share on Google+"
               onclick="popUp = window.open('https://plus.google.com/share?url=<?= Yii::app()->createAbsoluteUrl($this->model->getUrl()) ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false"><i
                        class="icon-google-plus"></i>
            </a>
        </li>
        <li class="">
            <a href="javascript:void(0);" title="Share on Vk"
               onclick="popUp = window.open('http://vk.com/share.php?url=<?= Yii::app()->createAbsoluteUrl($this->model->getUrl()) ?>&title=<?= $this->title ?>&image=<?= Yii::app()->createAbsoluteUrl($this->image) ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false"><i
                        class="icon-vk"></i>
            </a>
        </li>
    </ul>
</div>