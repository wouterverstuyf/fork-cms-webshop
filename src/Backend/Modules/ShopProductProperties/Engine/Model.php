<?php

namespace Backend\Modules\ShopProductProperties\Engine;

use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language;

/**
 * In this file we store all generic functions that we will be using in the ShopProductProperties module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Model
{
    const QRY_DATAGRID_BROWSE =
        'SELECT i.id, c.name, i.website
         FROM shop_product_properties AS i
         INNER JOIN shop_product_properties_content as c  on i.id = c.brand_id
         WHERE c.language = ?';

    /**
     * Delete a certain item
     *
     * @param int $id
     */
    public static function delete($id)
    {
        BackendModel::get('database')->delete('shop_product_properties', 'id = ?', (int) $id);
        BackendModel::get('database')->delete('shop_product_properties_content', 'brand_id = ?', (int) $id);
        BackendModel::get('database')->update('shop_products', array('brand_id' => NULL), 'brand_id = ?', array($id));
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
             FROM shop_product_properties AS i
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
             FROM shop_product_properties AS i
             WHERE i.id = ?',
            array((int) $id)
        );

        // data found
        $return['content'] = (array) $db->getRecords(
            'SELECT i.* FROM shop_product_properties_content AS i
            WHERE i.brand_id = ?',
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

        return (int) BackendModel::get('database')->insert('shop_product_properties', $item);
    }

    public static function insertContent(array $content)
    {
        BackendModel::get('database')->insert('shop_product_properties_content', $content);
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
            'shop_product_properties', $item, 'id = ?', (int) $item['id']
        );
    }

    public static function updateContent(array $content, $id)
    {
        $db = BackendModel::get('database');
        foreach($content as $language => $row)
        {
            $db->update('shop_product_properties_content', $row, 'brand_id = ? AND language = ?', array($id, $language));
        }
    }
}
