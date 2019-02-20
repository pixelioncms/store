<?php

Yii::import('mod.contacts.blocks.schedule.ScheduleWidget');

/**
 * ScheduleForm class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage schedule
 * @uses FormModel
 * 
 * @property string $phone Телефон
 */
class ScheduleWidgetForm extends WidgetFormModel {

    private $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    public $monday; //понедельник
    public $tuesday; //вторник
    public $wednesday; //среда
    public $thursday; //четверг
    public $friday; //пятница
    public $saturday; //суббота
    public $sunday; //воскресение

    /**
     * 
     * @return type
     */
    public $monday_after_time; //понедельник
    public $monday_before_time; //понедельник
    public $tuesday_after_time; //вторник
    public $tuesday_before_time; //вторник
    public $wednesday_after_time; //среда
    public $wednesday_before_time; //среда
    public $thursday_after_time; //четверг
    public $thursday_before_time; //четверг
    public $friday_after_time; //пятница
    public $friday_before_time; //пятница
    public $saturday_after_time; //суббота
    public $saturday_before_time; //суббота
    public $sunday_after_time; //воскресение
    public $sunday_before_time; //воскресение

    public function rules() {
        return $this->getRulesArray();
    }

    public function getForm() {
        Yii::import('app.jui.JuiDateTimePicker');
        return array(
            'type' => 'form',
            'attributes' => array(
                'id' => __CLASS__
            ),
            'elements' => $this->getFormTimesArray(),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
        );
    }

    private function getFormTimesArray() {
        $result = array();
        foreach ($this->days as $day) {

            $result[$day] = array(
                'type' => 'checkbox',
            );
            $result[$day . '_after_time'] = array(
                'type' => 'JuiDateTimePicker',
                'mode' => 'time',
                'options' => array(
                    'timeFormat' => 'HH:mm'
                ),
            );
            $result[$day . '_before_time'] = array(
                'type' => 'JuiDateTimePicker',
                'mode' => 'time',
                'options' => array(
                    'timeFormat' => 'HH:mm'
                ),
            );
        }
        return $result;
    }

    private function getRulesArray() {
        $result = array();
        foreach ($this->days as $day) {
            $result[] = array($day, 'boolean');
            $result[] = array($day . '_after_time', 'type', 'type' => 'datetime', 'datetimeFormat' => 'hh:mm');
            $result[] = array($day . '_before_time', 'type', 'type' => 'datetime', 'datetimeFormat' => 'hh:mm');
            $result[] = array($day, 'validateTime');
        }
        return $result;
    }

    
    public function validateTime($attr) {
        $after = "{$attr}_after_time";
        $before ="{$attr}_before_time";
        if ($this->$attr) {
            if (empty($this->$after)) {
                $this->addError($after, Yii::t('ScheduleWidget.default', 'ERROR_VALID_TIME'));
            }
            if (empty($this->$before)) {
                $this->addError($before, Yii::t('ScheduleWidget.default', 'ERROR_VALID_TIME'));
            }
        }
    }

    public function attributeLabels() {
        $result = array();
        foreach ($this->days as $day) {
            $result[$day] = Yii::t('ScheduleWidget.default', strtoupper($day));
            $result[$day . '_after_time'] = Yii::t('ScheduleWidget.default', strtoupper($day . '_after_time'));
            $result[$day . '_before_time'] = Yii::t('ScheduleWidget.default', strtoupper($day . '_before_time'));
        }
        return $result;
    }

    public function registerScript() {
        $cs = Yii::app()->clientScript;
        $cs->registerScript(get_class($this), "
            $(function(){
                $(['monday','tuesday','wednesday','thursday','friday','saturday','sunday']).each(function( index, element ) {
                    var sel_list = '.field_'+element+'_after_time, .field_'+element+'_before_time';
                    $('#ScheduleForm_'+element).change(function(){
                        common.hasChecked('#ScheduleForm_'+element, sel_list);
                    });
                    common.hasChecked('#ScheduleForm_'+element, sel_list);
                });
            });
        ", CClientScript::POS_HEAD);
    }

}
