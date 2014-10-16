<?php

/*
 * This file is part of the SinglePageBundle.
 *
 * (c) Jean-Christophe Meillaud <jc@houseofagile.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HOA\Bundle\SinglePageBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SinglePageSymlinkCommand extends StandardSymlinkCommand
{
    public static $HOASinglePageBundleName = "houseofagile/single-page-boostrap-bundle";


    public static $resourcesBundle = array(
        "ironsummitmedia/startbootstrap" => 'start-bootstrap',
        "mopa/bootstrap-bundle" => 'mopa-bootstrap',
        "twbs/bootstrap" => 'twbs-bootstrap'
    );

    protected function configure()
    {
        $this
            ->setName('singlepage:link-bootstrap-resources')
            ->setHelp(<<<EOT
The <info>singlepage:link-bootstrap-resources</info> command helps you checking and symlinking/mirroring for the needed libraries.
EOT
            );
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        foreach (self::$resourcesBundle as $packageName => $packageTargetName) {
            $this->symlinkResource(self::$HOASinglePageBundleName, $packageName, $this->getTargetSuffix($packageTargetName));
        }
    }

    public static function getTargetSuffix($targetName = "")
    {
        return DIRECTORY_SEPARATOR . "Resources" . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "third-party" . DIRECTORY_SEPARATOR . $targetName;
    }
}
