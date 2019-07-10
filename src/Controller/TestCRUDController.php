<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 19.11.2018
 * Time: 15:52
 */

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOMySql\Driver as MySqlDriver;
use Doctrine\DBAL\Driver\PDOPgSql\Driver as PgDriver;

class TestCRUDController extends AbstractCRUDController
{
    public function listAction()
    {
        $r = [];
        $r['property'][] = [
            'name' => 'date_default_timezone_get()',
            'value' => date_default_timezone_get(),
        ];
        /** @var Connection $connection */
        $connection = $this->getConnection();
        $driver = $connection->getDriver();
        if ($driver instanceof MySqlDriver) {
            $sql = 'SELECT @@SESSION.time_zone as r';
        } else if ($driver instanceof PgDriver) {
            $sql = 'SELECT current_setting(\'TIMEZONE\') as r';
        }

        $rows = $this->getConnection()->query($sql)->fetchAll();
        $r['property'][] = [
            'name' => $sql,
            'value' => $rows[0]['r'],
        ];

        $date = new \DateTime();
        $r['property'][] = [
            'name' => 'new \DateTime()',
            'value' => $date->format('Y-m-d H:i:s'),
        ];

        $sql = 'SELECT NOW() as r';
        $rows = $this->getConnection()->query($sql)->fetchAll();
        $r['property'][] = [
            'name' => $sql,
            'value' => $rows[0]['r'],
        ];

        $date = new \DateTime();
        if ($driver instanceof MySqlDriver) {
            $sql = 'SELECT STR_TO_DATE(\'' . $date->format('Y-m-d H:i:s') . '\', \'%Y-%m-%d %H:%i:%s\') as r';
        } else if ($driver instanceof PgDriver) {
            $sql = 'SELECT to_timestamp(\'' . $date->format('Y-m-d H:i:s') . '\', \'YYYY-MM-DD HH24:MI:SS\') as r';
        }

        $rows = $this->getConnection()->query($sql)->fetchAll();
        $r['property'][] = [
            'name' => $sql,
            'value' => $rows[0]['r'],
        ];

        $r['bloc'] = [];
        if ($driver instanceof PgDriver) {
            $sql = '
                SELECT blocked_locks.pid         AS blocked_pid,
                       blocked_activity.usename  AS blocked_user,
                       blocking_locks.pid        AS blocking_pid,
                       blocking_activity.usename AS blocking_user,
                       blocked_activity.query    AS blocked_statement,
                       blocking_activity.query   AS current_statement_in_blocking_process
                FROM  pg_catalog.pg_locks          blocked_locks
                  JOIN pg_catalog.pg_stat_activity blocked_activity  ON blocked_activity.pid = blocked_locks.pid
                  JOIN pg_catalog.pg_locks         blocking_locks
                    ON blocking_locks.locktype = blocked_locks.locktype
                       AND blocking_locks.DATABASE IS NOT DISTINCT FROM blocked_locks.DATABASE
                       AND blocking_locks.relation IS NOT DISTINCT FROM blocked_locks.relation
                       AND blocking_locks.page IS NOT DISTINCT FROM blocked_locks.page
                       AND blocking_locks.tuple IS NOT DISTINCT FROM blocked_locks.tuple
                       AND blocking_locks.virtualxid IS NOT DISTINCT FROM blocked_locks.virtualxid
                       AND blocking_locks.transactionid IS NOT DISTINCT FROM blocked_locks.transactionid
                       AND blocking_locks.classid IS NOT DISTINCT FROM blocked_locks.classid
                       AND blocking_locks.objid IS NOT DISTINCT FROM blocked_locks.objid
                       AND blocking_locks.objsubid IS NOT DISTINCT FROM blocked_locks.objsubid
                       AND blocking_locks.pid != blocked_locks.pid
                
                  JOIN pg_catalog.pg_stat_activity blocking_activity ON blocking_activity.pid = blocking_locks.pid
                WHERE NOT blocked_locks.GRANTED';
            $r['bloc'] = $this->getConnection()->fetchAll($sql);
        }

        if ($driver instanceof MySqlDriver) {
            $sql = '
                SELECT id pid, time age, user usename, info query
                FROM INFORMATION_SCHEMA.PROCESSLIST';
        } else if ($driver instanceof PgDriver) {
            $sql = '
                SELECT pid, age(clock_timestamp(), query_start), usename, query
                FROM pg_stat_activity
                WHERE query != \'<IDLE>\' AND query NOT ILIKE \'%pg_stat_activity%\'
                ORDER BY query_start DESC';
        }
        $r['query'] = $this->getConnection()->fetchAll($sql);

        return $this->renderWithExtraParams('admin/admin_test.html.twig', $r);
    }
}