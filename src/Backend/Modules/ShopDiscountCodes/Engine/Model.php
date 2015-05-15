<?php

namespace Backend\Modules\ShopDiscountCodes\Engine;

use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language;

/**
 * In this file we store all generic functions that we will be using in the Shop Shipping module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Model
{
    const QRY_DATAGRID_BROWSE =
        'SELECT i.id, i.name, i.discount, i.discount_type
         FROM shop_discount_codes AS i';

    /**
     * Delete a certain item
     *
     * @param int $id
     */
    public static function delete($id)
    {
        BackendModel::get('database')->delete('shop_discount_codes', 'id = ?', (int) $id);
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
             FROM shop_discount_codes AS i
             WHERE i.id = ?
             LIMIT 1',
            array((int) $id)
        );
    }

     public static function existsByCode($code)
    {
        return (bool) BackendModel::get('database')->getVar(
            'SELECT 1
             FROM shop_discount_codes AS i
             WHERE i.code = ?
             LIMIT 1',
            array((string) $code)
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
        return (array) BackendModel::get('database')->getRecord(
            'SELECT i.*
             FROM shop_discount_codes AS i
             WHERE i.id = ?',
            array((int) $id)
        );
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

        return (int) BackendModel::get('database')->insert('shop_discount_codes', $item);
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
            'shop_discount_codes', $item, 'id = ?', (int) $item['id']
        );
    }
}
