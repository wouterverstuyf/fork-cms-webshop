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

    public static function insertTreeNode($child, $parent)
    {
        // build query
        $query = 'INSERT INTO shop_categories_treepaths (ancestor, descendant, length)
            SELECT t.ancestor, :child, t.length+1
            FROM shop_categories_treepaths AS t
            WHERE t.descendant = :parent
            UNION ALL
            SELECT :child, :child, 0;';

        // set the query parameters for the insert-part
        $parameters = array();
        $parameters['child'] = (int) $child;
        $parameters['parent'] = (int) $parent;

        // execute the query
        return BackendModel::get('database')->execute($query, $parameters);
    }


    public static function getRootNodes()
    {   
        // The root node of a tree is the node that has no ancestors, besides itself.
        // http://karwin.blogspot.be/2010/03/rendering-trees-with-closure-tables.html
        // build query
        $query = 'SELECT c.* FROM shop_categories_treepaths AS c
            LEFT OUTER JOIN shop_categories_treepaths AS anc
            ON anc.descendant = c.descendant AND anc.ancestor <> c.ancestor
            WHERE anc.ancestor IS NULL';

        // execute the query
        return BackendModel::get('database')->getRecords($query);
    }

    public static function getLeaveNodes()
    {
        // Finding nodes with no descendent besides themselves.
        // http://karwin.blogspot.be/2010/03/rendering-trees-with-closure-tables.html
        // build query
        $query = 'SELECT c.* FROM shop_categories_treepaths AS c
            LEFT OUTER JOIN shop_categories_treepaths AS des
            ON des.ancestor = c.ancestor AND des.descendant <> c.descendant
            WHERE des.descendant IS NULL';

        // execute the query
        return BackendModel::get('database')->getRecords($query);
    }

    public static function getTree()
    {   
        // @todo see http://stackoverflow.com/questions/8252323/mysql-closure-table-hierarchical-database-how-to-pull-information-out-in-the-c/8288201#8288201 for visual
        // the whole tree
        // http://karwin.blogspot.be/2010/03/rendering-trees-with-closure-tables.html
        // build query
        $query = 'SELECT *, c.name FROM shop_categories a
                    join shop_categories_treepaths b
                    on a.id=b.descendant
                    LEFT JOIN shop_categories_content as c ON c.category_id = a.id
                    where ancestor in (
                    SELECT c.ancestor FROM shop_categories_treepaths AS c
                    LEFT OUTER JOIN shop_categories_treepaths AS anc
                    ON anc.descendant = c.descendant AND anc.ancestor <> c.ancestor
                    WHERE anc.ancestor IS NULL) AND c.language = ?';

        // execute the query
        return BackendModel::get('database')->getRecords($query, array(Language::getWorkingLanguage()));
    }


    public static function moverTeeNode($node, $to)
    {
        // build query
        $query = 'INSERT INTO shop_categories_treepaths (ancestor, descendant, length)
            SELECT supertree.ancestor, subtree.descendant,
            supertree.length+subtree.length+1
            FROM shop_categories_treepaths AS supertree JOIN shop_categories_treepaths AS subtree
            WHERE subtree.ancestor =  :node
            AND supertree.descendant = :to';

        // set the query parameters for the insert-part
        $parameters = array();
        $parameters['node'] = (int) $node;
        $parameters['to'] = (int) $to;

        // execute the query
        return BackendModel::get('database')->execute($query, $parameters);
    }

    public static function deleteTreeNode($id)
    {
        // build query
        $query = 'DELETE a FROM shop_categories_treepaths AS a
            JOIN shop_categories_treepaths AS d ON a.descendant = d.descendant
            LEFT JOIN shop_categories_treepaths AS x
            ON x.ancestor = d.ancestor AND x.descendant = a.ancestor
            WHERE d.ancestor = :id AND x.ancestor IS NULL';

        // set the query parameters for the insert-part
        $parameters = array();
        $parameters['id'] = (int) $id;

        // execute the query
        return BackendModel::get('database')->execute($query, $parameters);
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
