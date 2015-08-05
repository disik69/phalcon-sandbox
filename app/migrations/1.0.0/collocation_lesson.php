<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class CollocationLessonMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'collocation_lesson',
            array(
                'columns' => array(
                    new Column(
                        'id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        )
                    ),
                    new Column(
                        'lesson_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'collocation_id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'lesson_id'
                        )
                    ),
                    new Column(
                        'alt_rus',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'collocation_id'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id')),
                    new Index('lesson_id', array('lesson_id')),
                    new Index('collocation_id', array('collocation_id'))
                ),
                'references' => array(
                    new Reference('collocation_lesson_ibfk_1', array(
                        'referencedSchema' => 'english_roulette',
                        'referencedTable' => 'lessons',
                        'columns' => array('lesson_id'),
                        'referencedColumns' => array('id')
                    )),
                    new Reference('collocation_lesson_ibfk_2', array(
                        'referencedSchema' => 'english_roulette',
                        'referencedTable' => 'collocations',
                        'columns' => array('collocation_id'),
                        'referencedColumns' => array('id')
                    ))
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ),
            )
        );
    }
}
