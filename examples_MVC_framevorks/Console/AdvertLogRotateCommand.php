<?php
namespace Console;

use Entity\Log;
use Silicone\Doctrine\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AdvertLogRotateCommand extends Command
{
    protected $batchSize=20;
    protected $output;
    protected $input;
    protected function configure()
    {
        $this
            ->setName('advert:logRotate')
            ->setDescription('Rotation logs directs parse')
            ->addOption(
               'monthly',
               null,
               InputOption::VALUE_NONE,
               'Set option for rotate logs monthly'
            )
            ->addOption(
               'dayly',
               null,
               InputOption::VALUE_NONE,
               'Set option for rotate logs dayly'
            )    
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(3600);
        $this->output = $output;
        $this->input = $input;
        $this->output->writeln('Start...  '.date('d.m.Y h:i:s'));
        $this->output->writeln("Memory usage before: " . (memory_get_usage() / 1024) . " KB" . PHP_EOL);
        
        if ($this->input->getOption('monthly')) 
            $this->roteteMonthly();
        elseif ($this->input->getOption('dayly')) 
            $this->rotateDayly();
        else 
            $this->roteteAll();
            
        
        $this->output->writeln("Memory usage after: " . (memory_get_usage() / 1024) . " KB" . PHP_EOL);
        $this->output->writeln('Finish.  '.date('d.m.Y h:i:s'));
    }
    
    protected function rotateDayly() {
        $q = $this->app->entityManager()->createQuery("select u
                                                            , avg(u.Shows) as avgShows 
                                                            , avg(u.Clicks) as avgClicks
                                                            , avg(u.Min) as avgMin
                                                            , avg(u.Max) as avgMax
                                                            , avg(u.PremiumMin) as avgPremiumMin
                                                            , avg(u.PremiumMax) as avgPremiumMax
                                                       from Entity\\Log u 
                                                       WHERE u.Type=1 
                                                         and u.cdate>CURRENT_DATE() 
                                                       GROUP BY u.PhraseID");
        $iterableResult = $q->iterate();
        $batchSize = 20;
        $i=0;
        $this->app->entityManager()->clear();
        while (($row = $iterableResult->next()) !== false) {
            if ($i==0) {
                $this->app->entityManager()->createQuery("DELETE Entity\\Log u 
                                                  WHERE u.Type=2 
                                                    and u.cdate=CURRENT_DATE()")
                                           ->execute();
            }
            $log = $row[0][0];
            $log->setShows($row[$i]['avgShows']);
            $log->setClicks($row[$i]['avgClicks']);
            $log->setMin($row[$i]['avgMin']);
            $log->setMax($row[$i]['avgMax']);
            $log->setPremiumMin($row[$i]['avgPremiumMin']);
            $log->setPremiumMax($row[$i]['avgPremiumMax']);
            $log->setType(2);
            $date = new \DateTime();
            $date->setTime(0,0,0);
            $log->setcdate($date);
            $i++;
            $this->app->entityManager()->persist($log);
            if (($i % $batchSize) == 0) {
                $this->app->entityManager()->flush();
                $this->app->entityManager()->clear();
            }
        }
        $this->app->entityManager()->flush();
        $this->app->entityManager()->clear();
        $this->app->entityManager()->createQuery("DELETE Entity\\Log u 
                                                  WHERE u.Type=1 
                                                    and u.cdate>CURRENT_DATE()")
                                   ->execute();
        
    }
    
    protected function roteteMonthly() {
              
    }
    
    protected function rotateAll() {
                   
    }
    
}
?>
