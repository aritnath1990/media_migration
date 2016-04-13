<?php

/**
 * @file
 * Contains \Drupal\migrate_nd\Plugin\migrate\source\MediaeNode.
 */

namespace Drupal\migrate_nd\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for sessions content.
 *
 * @MigrateSource(
 *   id = "mediae_node"
 * )
 */
class MediaeNode extends SqlBase {

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
    $query = $this->select('migrate_nd_mde_node', 'b')
                 ->fields('b', ['ebid', 'title','dt_event','eventbody','aid','sbid']);
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'ebid' => $this->t('Event ID'),
      'title' => $this->t('Title of Event'),
      'dt_event' => $this->t('Event date'),
      'eventbody' => $this->t('Body of Event'),
      'aid' => $this->t('Auther'),
      'sbid' => $this->t('session id'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'ebid' => [
        'type' => 'integer',
        'alias' => 'b',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    /**
     * As explained above, we need to pull the style relationships into our
     * source row here, as an array of 'style' values (the unique ID for
     * the media_term migration).
     */
    
    $sbidr = $this->select('migrate_nd_mdstemp_node', 'e')
                 ->fields('e', ['sbid'])
                 ->condition('eventid', $row->getSourceProperty('ebid'))
				 ->execute()
				 ->fetchCol();
      
 
      
    $row->setSourceProperty('sbid', $sbidr);
    $row->setSourceProperty('eventbody', "Hi iam try to check to send multiple value using row");
    return parent::prepareRow($row);
  }

}
