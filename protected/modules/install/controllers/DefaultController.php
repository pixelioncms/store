<?php

Yii::import('mod.install.forms.*');
Yii::import('mod.users.UsersModule');

class DefaultController extends CController
{

    protected $_assetsUrl = false;
    public $layout = 'install';
    public $process;
    public $title;
    public $cacheTime = 0;
    public $isAdminController = true;

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getCopyright()
    {
        return Yii::t('app', 'COPYRIGHT_APP', array(
            '{year}' => date('Y'),
            '{v}' => $this->getVersion(),
            '{site_name}' => CHtml::link(Yii::app()->name, '//pixelion.com.ua', array('title' => Yii::app()->name, 'target' => '_blank'))
        ));
    }

    public function actionIndex($step = null)
    {
        $config = array(
            'steps' => array(
                Yii::t('InstallModule.default', 'STEP_START') => 'chooseLanguage',
                Yii::t('InstallModule.default', 'STEP_LICENSE') => 'license',
                Yii::t('InstallModule.default', 'STEP_INFO') => 'info',
                Yii::t('InstallModule.default', 'STEP_DB') => 'db',
                Yii::t('InstallModule.default', 'STEP_CONFIGURE') => 'configure',
            ),
            'menuProperties' => array(
                'htmlOptions' => array('class' => 'list-unstyled nav-step'),
                'activeCssClass' => 'active',
                'firstItemCssClass' => 'first',
                'lastItemCssClass' => 'last',
                'previousItemCssClass' => 'success'
            ),
            'autoAdvance' => false,
            'events' => array(
                'onStart' => 'wizardStart',
                'onProcessStep' => 'wizardProcessStep',
                'onFinished' => 'wizardFinished',
                'onInvalidStep' => 'wizardInvalidStep',
                //   'onSaveDraft' => 'wizardSaveDraft'
            ),
            'menuLastItem' => Yii::t('InstallModule.default', 'STEP_COMPLETED')
        );
        if (!empty($config)) {
            $config['class'] = 'app.behaviors.WizardBehavior';
            $this->attachBehavior('wizard', $config);
        }


        $this->process($step);
    }

    // Wizard Behavior Event Handlers

    /**
     * Raised when the wizard starts; before any steps are processed.
     * MUST set $event->handled=true for the wizard to continue.
     * Leaving $event->handled===false causes the onFinished event to be raised.
     * @param WizardEvent The event
     */
    public function wizardStart($event)
    {
        $event->handled = true;
    }

    /**
     * Raised when the wizard detects an invalid step
     * @param WizardEvent The event
     */
    public function wizardInvalidStep($event)
    {
        Yii::app()->getUser()->setFlash('notice', $event->step . ' is not a vaild step in this wizard');
    }

    /**
     * The wizard has finished; use $event->step to find out why.
     * Normally on successful completion ($event->step===true) data would be saved
     * to permanent storage; the demo just displays it
     * @param WizardEvent The event
     */
    public function wizardFinished($event)
    {
        if ($event->step === true)
            $this->render('completed', compact('event'));
        else
            $this->render('finished', compact('event'));

        $event->sender->reset();
        Yii::app()->end();
    }

    /**
     * Process wizard steps.
     * The event handler must set $event->handled=true for the wizard to continue
     * @param WizardEvent The event
     */
    public function wizardProcessStep($event)
    {
        $read = $event->sender->read();
        if (isset($read['chooseLanguage'])) {
            Yii::app()->setLanguage($read['chooseLanguage']['lang']);
        }


        $modelName = ucfirst($event->step);
        $model = new $modelName();
        $model->attributes = $event->data;
        $form = $model->getForm();

        switch ($event->step) {
            case 'db':
                if (isset($_POST['Db'])) {
                    $model->attributes = $_POST['Db'];
                    if ($model->validate()) {
                        Yii::app()->cache->flush();
                        $model->install();
                    }
                }
                break;
            case 'completed':
                Yii::app()->cache->flush();
                FileSystem::fs('assets', Yii::getPathOfAlias('webroot'))->cleardir();
                break;
            case 'configure':
                $data = $event->sender->read();
                if (isset($_POST['Configure'])) {
                    $model->attributes = $_POST['Configure'];
                    if ($model->validate()) {
                        $model->install($data);
                    }
                }
                break;
            default:
                break;
        }

        if ($form->submitted() && $form->validate()) {

            $event->sender->save($model->attributes);
            $event->handled = true;
        } else {
            if ($event->step == 'info') {
                $this->render('form', compact('event', 'form'));
            } else {
                if (file_exists(Yii::getPathOfAlias('mod.install.views.default') . DS . $event->step . '.php')) {
                    $this->render($event->step, compact('event', 'form'));
                } else {
                    $this->render('form', compact('event', 'form'));
                }
            }
        }
    }

    /**
     * @return string Timezone
     */
    public function getTimezone()
    {
        $user = Yii::app()->user;
        $defaultTimeZone = 'Europe/Kiev';
        if (!$user->isGuest) {
            if ($user->timezone) {
                $tz = $user->timezone;
            } elseif (isset(Yii::app()->session['timezone'])) {
                $tz = Yii::app()->session['timezone'];
            } else {
                $tz = $defaultTimeZone;
            }
        } else {
            if (isset(Yii::app()->session['timezone'])) {
                $tz = Yii::app()->session['timezone'];
            } else {
                $tz = $defaultTimeZone;
            }
        }
        return $tz;
    }

}
