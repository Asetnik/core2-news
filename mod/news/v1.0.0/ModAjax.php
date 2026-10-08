<?php
require_once 'core2/inc/ajax.func.php';

class ModAjax extends ajaxFunc {

    public function __construct(xajaxResponse $res) {
        parent::__construct($res);
        $this->module = 'news';
    }

    /**
     * Сохранение новости.
     */
    public function axSaveNews(array $data): xajaxResponse {
        $fields = [
            'title' => 'req',
            'body'  => 'req',
        ];

        if ($this->ajaxValidate($data, $fields)) {
            return $this->response;
        }

        try {
            if (empty($data['params']['edit'])) {
                $data['control']['created_at'] = date('Y-m-d H:i:s');
            }

            $this->saveData($data);
            $this->done($data);
        } catch (Exception $e) {
            $this->error[] = $e->getMessage();
            $this->displayError($data);
        }

        return $this->response;
    }
}
