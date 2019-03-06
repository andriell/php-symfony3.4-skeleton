<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 26.09.2018
 * Time: 10:03
 */

namespace App\Command;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class TestDbCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('app:test-db')
            ->setDescription('Test db connection')
            ->setHelp('Test db connection')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lock = $this->createLock();
        if (!$lock->acquire()) {
            throw new \Exception('Command ' . $this->getName(). ' already running. Ignore launch another one.');
        }
        $rows = $this->getEm()->getConnection()->fetchAll('SELECT version() as version;');
        $output->writeln($rows[0]['version']);
    }
}