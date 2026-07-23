<?php

/***[JCBGUI.power.licensing_template.136.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    4th September, 2022
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JCB\Joomla\Helloworld;


use JCB\Joomla\Interfaces\TableInterface;
use JCB\Joomla\Abstraction\BaseTable;


/**
 * Helloworld Table Definition Class.
 * 
 * This class provides a centralized definition of all database-backed
 * areas, views, and their fields for Helloworld.
 * 
 * It maps component entities to their schema definitions, field metadata,
 * storage behaviour, and relationships, enabling consistent code generation
 * and runtime operations across models, forms, and SQL migrations.
 * 
 * @since 3.2.0
 */
class Table extends BaseTable implements TableInterface
{

/***[JCBGUI.power.main_class_code.136.$$$$]***/
	/**
	 * Table Schema and Field Metadata Map.
	 *
	 * A fully structured, associative array containing definitions for
	 * every database table (area/view) used by the component. Each table
	 * contains its field definitions with the following details:
	 *
	 * - **name**: The column/field name in the database.
	 * - **label**: The Joomla language constant for field display text.
	 * - **type**: The form field type (text, textarea, editor, radio, list, etc.).
	 * - **title**: Whether this field is the primary title field for the view.
	 * - **list**: The list view this field belongs to.
	 * - **store**: Any special storage encoding (e.g. `base64`, `json`, `basic_encryption`).
	 * - **tab_name**: The logical tab grouping for form display.
	 * - **db**: An array describing the database column definition:
	 *   - `type`: The SQL column type (e.g. VARCHAR(255), INT(11)).
	 *   - `default`: Default value or `EMPTY`.
	 *   - `GUID`: A unique identifier.
	 *   - `null_switch`: Whether NULL is allowed (`NULL` or `NOT NULL`).
	 *   - `unique_key`: Boolean indicating if a unique key is enforced.
	 *   - `key`: Boolean indicating if it is indexed.
	 * - **link**: If applicable, describes a foreign key relationship
	 *   (target table, component, entity, value field, and key field).
	 * - **fields**: (Optional) Subfield definitions for subforms.
	 *
	 * This array is the canonical reference used by developers to dynamically
	 * build database migrations, generate forms, create models, and ensure
	 * consistent schema management across installations and updates.
	 *
	 * @var   array
	 * @since 3.2.0
	 */
	protected array $tables = [
		'greeting' => [
			'greeting' => [
				'name' => 'greeting',
				'guid' => '75e830a6-a3a5-4327-9161-3f774a6f1591',
				'label' => 'COM_HELLOWORLD_GREETING_GREETING_LABEL',
				'type' => 'text',
				'title' => true,
				'list' => 'greetings',
				'store' => NULL,
				'tab_name' => 'Details',
				'db' => [
					'type' => 'VARCHAR(255)',
					'default' => '',
					'GUID' => '75e830a6-a3a5-4327-9161-3f774a6f1591',
					'null_switch' => 'NULL',
					'unique_key' => false,
					'key' => true,
				],
				'link' => NULL,
			],
			'alias' => [
				'name' => 'alias',
				'guid' => '335866ce-b81b-4329-901d-c20254135c9c',
				'label' => 'COM_HELLOWORLD_GREETING_ALIAS_LABEL',
				'type' => 'text',
				'title' => false,
				'list' => 'greetings',
				'store' => NULL,
				'tab_name' => 'Details',
				'db' => [
					'type' => 'CHAR(64)',
					'default' => '',
					'GUID' => '335866ce-b81b-4329-901d-c20254135c9c',
					'null_switch' => 'NULL',
					'unique_key' => false,
					'key' => true,
				],
				'link' => NULL,
			],
			'access' => [
				'name' => 'access',
				'label' => 'Access',
				'type' => 'accesslevel',
				'title' => false,
				'store' => NULL,
				'tab_name' => NULL,
				'db' => [
					'type' => 'INT(10) unsigned',
					'default' => '0',
					'key' => true,
					'null_switch' => 'NULL',
				],
			],
			'metakey' => [
				'name' => 'metakey',
				'label' => 'Meta Keywords',
				'type' => 'textarea',
				'title' => false,
				'store' => NULL,
				'tab_name' => 'publishing',
				'db' => [
					'type' => 'TEXT',
				],
			],
			'metadesc' => [
				'name' => 'metadesc',
				'label' => 'Meta Description',
				'type' => 'textarea',
				'title' => false,
				'store' => NULL,
				'tab_name' => 'publishing',
				'db' => [
					'type' => 'TEXT',
				],
			],
			'metadata' => [
				'name' => 'metadata',
				'label' => 'Meta Data',
				'type' => NULL,
				'title' => false,
				'store' => 'json',
				'tab_name' => 'publishing',
				'db' => [
					'type' => 'TEXT',
				],
			],
		],
	];/***[/JCBGUI$$$$]***/

}

