<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 06.11.2018
 * Time: 17:22
 */

namespace App\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin as AA;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;

class AbstractAdmin extends AA
{
    protected function addDateFromAndDateTo(DatagridMapper &$dataGridMapper, $field = 'date') {
        $dataGridMapper->add($field . '_from', 'doctrine_orm_callback', [
            'callback' => function ($queryBuilder, $alias, $f, $value) use ($field) {
                if (!$value['value']) return false;
                /** @var \DateTime $date */
                $date = $value['value'];
                /** @var $queryBuilder QueryBuilder */
                $queryBuilder->andWhere("{$alias}.{$field} >= :{$field}_from");
                $queryBuilder->setParameter("{$field}_from", $date);
                return true;
            }], DatePickerType::class, [
            'dp_language' => 'ru',
            'dp_min_date' => '01-01-2018',
            'dp_side_by_side' => true,
            'format' => 'dd-MM-yyyy',
            'dp_default_date' => date('d-m-Y'),
        ]);
        $dataGridMapper->add($field . '_to', 'doctrine_orm_callback', [
            'callback' => function ($queryBuilder, $alias, $f, $value) use ($field) {
                if (!$value['value']) return false;
                /** @var \DateTime $date */
                $date = $value['value'];
                /** @var $queryBuilder QueryBuilder */
                $queryBuilder->andWhere("{$alias}.{$field} <= :{$field}_to");
                $queryBuilder->setParameter("{$field}_to", $date);
                return true;
            }], DatePickerType::class, [
            'dp_language' => 'ru',
            'dp_min_date' => '01-01-2018',
            'dp_side_by_side' => true,
            'format' => 'dd-MM-yyyy',
            'dp_default_date' => date('d-m-Y', time() + 24 * 60 * 60),
        ]);
    }
}