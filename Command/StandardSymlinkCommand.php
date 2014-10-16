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

use Mopa\Bridge\Composer\Adapter\ComposerAdapter;
use Mopa\Bridge\Composer\Util\ComposerPathFinder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class StandardSymlinkCommand extends ContainerAwareCommand
{

    /**
     * @var $input InputInterface
     */
    protected $input;
    /**
     * @var $input OutputInterface
     */
    protected $output;

    /**
     * Checks symlink's existence.
     *
     * @param string $symlinkTarget The Target
     * @param string $symlinkName The Name
     * @param boolean $forceSymlink Force to be a link or throw exception
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public static function checkSymlink($symlinkTarget, $symlinkName, $forceSymlink = false)
    {
        if ($forceSymlink && file_exists($symlinkName) && !is_link($symlinkName)) {
            if ("link" != filetype($symlinkName)) {
                throw new \Exception($symlinkName . " exists and is no link!");
            }
        } elseif (is_link($symlinkName)) {
            $linkTarget = readlink($symlinkName);
            if ($linkTarget != $symlinkTarget) {
                if (!$forceSymlink) {
                    throw new \Exception(sprintf('Symlink "%s" points to "%s" instead of "%s"', $symlinkName, $linkTarget, $symlinkTarget));
                }
                unlink($symlinkName);

                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * Create the symlink.
     *
     * @param string $symlinkTarget The Target
     * @param string $symlinkName The Name
     *
     * @throws \Exception
     */
    public static function createSymlink($symlinkTarget, $symlinkName)
    {
        if (false === @symlink($symlinkTarget, $symlinkName)) {
            throw new \Exception("An error occurred while creating symlink" . $symlinkName);
        }
        if (false === $target = readlink($symlinkName)) {
            throw new \Exception("Symlink $symlinkName points to target $target");
        }
    }

    /**
     * Create the directory mirror.
     *
     * @param string $symlinkTarget The Target
     * @param string $symlinkName The Name
     *
     * @throws \Exception
     */
    public static function createMirror($symlinkTarget, $symlinkName)
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir($symlinkName);
        $filesystem->mirror(
            realpath($symlinkName . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $symlinkTarget),
            $symlinkName,
            null,
            array('copy_on_windows' => true, 'delete' => true, 'override' => true)
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function symlinkResource($targetPackageName, $sourcePackageName,$targetPath)
    {

        if (false !== $composer = ComposerAdapter::getComposer($this->input, $this->output)) {
            $cmanager = new ComposerPathFinder($composer);
            $options = array(
                'targetSuffix' => DIRECTORY_SEPARATOR . $targetPath,
                'sourcePrefix' => '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
            );
            list($symlinkTarget, $symlinkName) = $cmanager->getSymlinkFromComposer(
                $targetPackageName,
                $sourcePackageName,
                $options
            );
        } else {
            $this->output->writeln("<error>Could not find composer</error>");
            return;
        }
        // Automatically detect if on Win XP where symlink will allways fail
        if (PHP_OS == "WINNT") {
            $this->output->write("Checking destination");

            if (true === self::checkSymlink($symlinkTarget, $symlinkName)) {
                $this->output->writeln(" ... <comment>symlink already exists</comment>");
            } else {
                $this->output->writeln(" ... <comment>not existing</comment>");
                $this->output->writeln("Mirroring from: " . $symlinkName);
                $this->output->write("for target: " . $symlinkTarget);
                self::createMirror($symlinkTarget, $symlinkName);
            }
        } else {
            $this->output->write("Checking symlink");
            if (false === self::checkSymlink($symlinkTarget, $symlinkName, true)) {
                $this->output->writeln(" ... <comment>not existing</comment>");
                $this->output->writeln("Creating symlink: " . $symlinkName);
                $this->output->write("for target: " . $symlinkTarget);
                self::createSymlink($symlinkTarget, $symlinkName);
            }
        }

        $this->output->writeln(" ... <info>OK</info>");
    }

}
