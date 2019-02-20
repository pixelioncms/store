<?php

class PdfController extends AdminController {

    public function actionIndex() {
        $params = array();
        $model = new OrderProduct('search_pdf');
        if (!empty($_GET['OrderProduct']))
            $model->attributes = $_GET['OrderProduct'];

        if (isset($_GET['orderid'])) {
            $params['orderid'] = $_GET['orderid'];
            $view = 'orderTable';
        } else {
            $view = 'index';
        }
        $dataProvider = $model->search_pdf($params);
        $mPDF1 = Yii::app()->pdf->mpdf();
        $mPDF1->WriteHTML($this->renderPartial($view, array('dataProvider' => $dataProvider, 'model' => $model), true));
        $mPDF1->Output();
    }

    public function actionNormal() {
        $params = array();
        $model = new OrderProduct('search_pdf');
        if (!empty($_GET['OrderProduct']))
            $model->attributes = $_GET['OrderProduct'];

        if (isset($_GET['orderid'])) {
            $params['orderid'] = $_GET['orderid'];
        }
        $dataProvider = $model->search_pdf($params);
        $this->render('index', array('dataProvider' => $dataProvider, 'model' => $model));
    }

}
