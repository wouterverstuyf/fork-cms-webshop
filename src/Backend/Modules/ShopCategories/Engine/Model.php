<?php

namespace Backend\Modules\Shopcategories\Engine;

use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language;

/**
 * In this file we store all generic functions that we will be using in the Shopcategories module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Model
{
    const QRY_DATAGRID_BROWSE =
        'SELECT i.id, c.name
         FROM shop_categories AS i
         INNER JOIN shop_categories_content as c  on i.id = c.category_id
         WHERE c.language = ?';

    /**
     * Delete a certain item
     *
     * @param int $id
     */
    public static function delete($id)
    {
        BackendModel::get('database')->delete('shop_categories', 'id = ?', (int) $id);
        BackendModel::get('database')->delete('shop_categories_content', 'category_id = ?', (int) $id);
        BackendModel::get('database')->update('shop_products', array('category_id' => NULL), 'category_id = ?', array($id));
    }

    /**
     * Checks if a certain item exists
     *
     * @param int $id
     * @return bool
     */
    public static function exists($id)
    {
        return (bool) BackendModel::get('database')->getVar(
            'SELECT 1
             FROM shop_categories AS i
             WHERE i.id = ?
             LIMIT 1',
            array((int) $id)
        );
    }

    /**
     * Fetches a certain item
     *
     * @param int $id
     * @return array
     */
    public static function get($id)
    {
        $db = BackendModel::get('database');

        $return =  (array) $db->getRecord(
            'SELECT i.*
             FROM shop_categories AS i
             WHERE i.id = ?',
            array((int) $id)
        );

        // data found
        $return['content'] = (array) $db->getRecords(
            'SELECT i.* FROM shop_categories_content AS i
            WHERE i.category_id = ?',
            array((int) $id), 'language');

        return  $return;

    }


    /**
     * Insert an item in the database
     *
     * @param array $item
     * @return int
     */
    public static function insert(array $item)
    {
        $item['created_on'] = BackendModel::getUTCDate();
        $item['edited_on'] = BackendModel::getUTCDate();

        return (int) BackendModel::get('database')->insert('shop_categories', $item);
    }

    public static function insertContent(array $content)
    {
        BackendModel::get('database')->insert('shop_categories_content', $content);
    }

    /**
     * Updates an item
     *
     * @param array $item
     */
    public static function update(array $item)
    {
        $item['edited_on'] = BackendModel::getUTCDate();

        BackendModel::get('database')->update(
            'shop_categories', $item, 'id = ?', (int) $item['id']
        );
    }

    public static function updateContent(array $content, $id)
    {
        $db = BackendModel::get('database');
        foreach($content as $language => $row)
        {
            $db->update('shop_categories_content', $row, 'category_id = ? AND language = ?', array($id, $language));
        }
    }
}
