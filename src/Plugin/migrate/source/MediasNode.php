<?php

/**
 * @file
 * Contains \Drupal\migrate_nd\Plugin\migrate\source\MediasNode.
 */

namespace Drupal\migrate_nd\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for sessions content.
 *
 * @MigrateSource(
 *   id = "medias_node"
 * )
 */
class MediasNode extends SqlBase {

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
    $query = $this->select('migrate_nd_mdstemp_node', 'b')
                 ->fields('b', ['sbid', 'title','dt_session','descrip','aid','mbid','meytbid']);
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'sbid' => $this->t('Ssd ID'),
      'title' => $this->t('Title of ssd'),
      'dt_session' => $this->t('Ssd date'),
      'descrip' => $this->t('Title of description'),
      'aid' => $this->t('Auther'),
      'mbid' => $this->t('Teacher id'),
      'meytbid' => $this->t('mediaentity id'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'sbid' => [
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
    
    /**$sbidref = $this->select('migrate_nd_mdmyt_node', 'bt')
                 ->fields('bt', ['meytbid'])
      ->condition('meytbid', $row->getSourceProperty('meytbid'))
      ->execute()
      ->fetchCol();
    $row->setSourceProperty('meytbid', $sbidref);*/

    return parent::prepareRow($row);
  }

}
