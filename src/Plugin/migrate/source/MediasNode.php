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
      'mebid' =>  $this->t('mebid id'),
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
      ->fetchCol();*/
      //=========================================
      
        $fields = array('bbid', 'style');


  $obj = db_query('SELECT title FROM migrate_nd_mdstemp_node WHERE sbid='.$row->getSourceProperty('sbid'));

  $i=0;
      $j=0;
      $j1=0;
      
      foreach($obj as $obj1)
      {
		  $q1 = db_query("SELECT recordings FROM migrate_nd_sestemp_node WHERE title='".$obj1->title."'");
          foreach($q1 as $r)
           {
			   $data1[$i]=$r->recordings;
			   $i=$i+1;
		   }
	  }
	  $data1count=count($data1);
      for ($xyz=0;$xyz<$data1count;$xyz++)
	  {
		  $q2 = db_query("SELECT meytbid FROM migrate_nd_mdmyt_node WHERE filename='".$data1[$xyz]."'");
		  
          foreach($q2 as $r2)
          {
			  $meytbidref[$j]=$r2->meytbid;
			  $j=$j+1;
		  }
          
          $q3 = db_query("SELECT mebid FROM migrate_nd_mdm_node WHERE filename='".$data1[$xyz]."'");
          foreach($q3 as $r3)
          {
			  $mebidref[$j1]=$r3->mebid;
			  $j1=$j1+1;
		  }
      }

      
    $row->setSourceProperty('meytbid', $meytbidref);
    $row->setSourceProperty('mebid',$mebidref);
    return parent::prepareRow($row);
  }

}
