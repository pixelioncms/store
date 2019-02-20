<?php

class MarkupHelper extends CComponent {

    /**
     * Get roles and prepare to display in dropdownlist
     *
     * @return array
     */
    public static function getRoles() {
        $roles = Yii::app()->db->createCommand()
                ->select('name')
                ->from('{{authitem}}')
                ->where('type=2')
                ->queryColumn();

        return array_combine($roles, $roles);
    }

}