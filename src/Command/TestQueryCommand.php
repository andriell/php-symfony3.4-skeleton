<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestQueryCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('app:test-query')
            ->setDescription('Test query');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lock = $this->createLock();
        if (!$lock->acquire()) {
            throw new \Exception('Command ' . $this->getName(). ' already running. Ignore launch another one.');
        }

        $logger = new \Doctrine\DBAL\Logging\DebugStack();
        $this->getContainer()->get('doctrine')->getConnection()->getConfiguration()->setSQLLogger($logger);


        // Type your query here

        $log = '';
        foreach ($logger->queries as $q) {
            $sql = $q['sql'];
            foreach ($q['params'] as $name => $param) {
                if ($param instanceof \DateTime) {
                    $param = "'" . $param->format('Y-m-d H:i:s') . "'";
                } elseif (is_array($param)) {
                    $param = implode(', ', $param);
                } else {
                    $param = "'" . $param . "'";
                }
                $sql = preg_replace('#\?#', $param, $sql, 1);
                $sql = str_replace(':' . $name,  $param, $sql);
            }
            $log .= $sql . "\n";
            $log .= '# Execution:' . $q['executionMS'] . " ms\n\n";
        }
        $output->writeln($log);
    }
}