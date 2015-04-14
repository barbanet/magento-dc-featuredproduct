<?php
/**
 * Dc_FeaturedProduct
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Dc
 * @package    Dc_FeaturedProduct
 * @copyright  Copyright (c) 2015 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('featuredproduct/group')};
CREATE TABLE {$this->getTable('featuredproduct/group')} (
    group_id int(11) unsigned NOT NULL auto_increment,
    name varchar(255) NOT NULL,
    PRIMARY KEY (group_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('featuredproduct/item')};
CREATE TABLE {$this->getTable('featuredproduct/item')} (
    item_id int(11) unsigned NOT NULL auto_increment,
    group_id int(11) unsigned NOT NULL,
    product_id int(11) unsigned NOT NULL,
    position int(11) unsigned NOT NULL,
    PRIMARY KEY (item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE {$this->getTable('featuredproduct/item')} ADD CONSTRAINT FK_FEATUREDPRODUCT_ITEM_GROUP_ID
        FOREIGN KEY (group_id) REFERENCES {$installer->getTable('featuredproduct/group')} (group_id)
        ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS {$this->getTable('featuredproduct/template')};
CREATE TABLE {$this->getTable('featuredproduct/template')} (
    template_id int(11) unsigned NOT NULL auto_increment,
    name varchar(255) NOT NULL,
    file_path varchar(255) NOT NULL,
    PRIMARY KEY (template_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();
