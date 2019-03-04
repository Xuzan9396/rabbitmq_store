<?php
namespace Store2\Modules\Cli\Tasks;

class VersionTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
        $config = $this->getDI()->get('config');

        while (true) {
            echo $config['version'];
            sleep(1);

        }
    }

}
