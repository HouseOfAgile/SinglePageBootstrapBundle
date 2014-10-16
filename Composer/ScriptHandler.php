<?php

/*
 * This file is part of the SinglePageBundle.
 *
 * (c) Jean-Christophe Meillaud <jc@houseofagile.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HOA\Bundle\SinglePageBundle\Composer;

use Composer\Script\Event;
use HOA\Bundle\SinglePageBundle\Command\SinglePageSymlinkCommand;
use HOA\Bundle\SinglePageBundle\Command\StartBootstrapSymlinkCommand;
use Mopa\Bridge\Composer\Util\ComposerPathFinder;

class ScriptHandler
{

    public static function postInstallSymlinkStartBootstrap(Event $event)
    {
        $IO = $event->getIO();
        $composer = $event->getComposer();
        $cmanager = new ComposerPathFinder($composer);

        foreach (SinglePageSymlinkCommand::$resourcesBundle as $packageName => $packageTargetName) {

            $options = array(
                'targetSuffix' => DIRECTORY_SEPARATOR . SinglePageSymlinkCommand::getTargetSuffix($packageTargetName),
                'sourcePrefix' => '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
            );
            list($symlinkTarget, $symlinkName) = $cmanager->getSymlinkFromComposer(
                SinglePageSymlinkCommand::$HOASinglePageBundleName,
                $packageName,
                $options
            );
            $IO->write("Checking Symlink", FALSE);
            if (false === SinglePageSymlinkCommand::checkSymlink($symlinkTarget, $symlinkName, true)) {
                $IO->write("Creating Symlink: " . $symlinkName, FALSE);
                SinglePageSymlinkCommand::createSymlink($symlinkTarget, $symlinkName);
            }
            $IO->write(" ... <info>OK</info>");
        }
    }


}
