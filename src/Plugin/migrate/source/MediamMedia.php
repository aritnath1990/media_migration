<?php

/**
 * @file
 * Contains \Drupal\migrate_nd\Plugin\migrate\source\MediamMedia.
 */

namespace Drupal\migrate_nd\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for sessions content.
 *
 * @MigrateSource(
 *   id = "mediam_media"
 * )
 */
class MediamMedia extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    /**
     * An important point to note is that your query *must* return a single row
     * for each item to be imported. Here we might be tempted to add a join to
     * media_mg_media_topic_node in our query, to pull in the
     * relationships to our categories. Doing this would cause the query to
     * return multiple rows for a given node, once per related value, thus
     * processing the same node multiple times, each time with only one of the
     * multiple values that should be imported. To avoid that, we simply query
     * the base node data here, and pull in the relationships in prepareRow()
     * below.
     */
    $query = $this->select('migrate_nd_mdm_node', 'b')
                 ->fields('b', ['mebid', 'title','aid','uril','filename']);
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'mebid' => $this->t('Ssd ID'),
      'title' => $this->t('Title of ssd'),
      'aid' => $this->t('Auther'),
      'uril' => $this->t('Auther'),
      'filename' => $this->t('Auther'),

    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'mebid' => [
        'type' => 'integer',
        'alias' => 'b',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
   // Row is a now a class with helpful methods.
    // Set the complete external path to the image.
    
   
    return parent::prepareRow($row);
  }
}