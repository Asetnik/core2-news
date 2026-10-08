<?php
require_once DOC_ROOT . 'core2/inc/classes/class.list.php';
require_once DOC_ROOT . 'core2/inc/classes/class.edit.php';
require_once DOC_ROOT . 'core2/inc/classes/Panel.php';

/**
 * Class ModNewsController
 */
class ModNewsController extends Common {

    public function action_index() {
        $app   = "index.php?module={$this->module}&action=index";
        $panel = new \Core2\Panel('news');

        if (isset($_GET['edit']) && $_GET['edit'] !== '') {
            $id = (int)$_GET['edit'];
            $panel->setTitle($id ? 'Редактирование новости' : 'Новая новость', '', $app);
            $panel->setContent($this->editForm($app, $id));
        } else {
            $panel->setTitle('Новости');
            $panel->setContent($this->listTable($app));
        }

        return $panel->render();
    }

    private function editForm(string $app, int $id): string {
        $edit = new editTable($this->resId);
        $edit->SQL = "
            SELECT id,
                   title,
                   announce,
                   body,
                   is_active_sw
            FROM mod_news
            WHERE id = {$id}
        ";

        $edit->addControl('Заголовок:', 'TEXT', 'maxlength="255" size="60"', '', '', true);
        $edit->addControl('Анонс:', 'TEXTAREA', 'cols="57" rows="3"', '', '');
        $edit->addControl('Текст:', 'TEXTAREA', 'cols="57" rows="10"', '', '', true);

        $edit->back = $app;
        $edit->save('xajax_saveNews(xajax.getFormValues(this.id))');
        $edit->addButton('Отменить', "load('{$app}')");

        return $edit->render();
    }

    private function listTable(string $app): string {
        $list = new listTable($this->resId);
        $list->table = 'mod_news';

        $list->addSearch('Заголовок', 'title', 'TEXT');
        $list->addSearch('Анонс', 'announce', 'TEXT');

        $list->SQL = "
            SELECT id,
                   title,
                   announce,
                   created_at,
                   is_active_sw
            FROM mod_news
            WHERE 1=1 ADD_SEARCH
            ORDER BY id DESC
        ";

        $list->addColumn('Заголовок', '', 'TEXT');
        $list->addColumn('Анонс', '', 'TEXT');
        $list->addColumn('Создано', '140', 'TEXT');
        $list->addColumn('', '1%', 'STATUS_INLINE', 'mod_news.is_active_sw');

        $list->addURL    = $app . '&edit=0';
        $list->editURL   = $app . '&edit=TCOL_00';
        $list->deleteKey = 'mod_news.id';

        return $list->render();
    }
}
