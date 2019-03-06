<?php
/**
 * Created by PhpStorm.
 * User: am_ry
 * Date: 06.11.2018
 * Time: 12:17
 */

namespace App\Command;


use BackupManager\Filesystems\Destination;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;
use Symfony\Component\Finder\Finder;

class DbBackupCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('app:db-backup')
            ->setDescription('Database backup')
            ->setHelp('Database backup');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lock = $this->createLock();
        if (!$lock->acquire()) {
            throw new \Exception('Command ' . $this->getName(). ' already running. Ignore launch another one.');
        }

        $manager = $this->getBackupManager();
        $manager->makeBackup()->run('db', [new Destination('local', date('Y_m_d') . '_backup.sql')], 'gzip');
        $output->writeln('Backup - done');

        $backupDir = $this->getParameter('backup_dir');
        $finder = new Finder();
        $finder->files()->in($backupDir);
        foreach ($finder as $file) {
            if ($file->getCTime() + 30 * 24 * 60 * 60 < time()) {
                unlink($file->getRealPath());
            }
        }

        $output->writeln('Delete old backups - done');
    }

}