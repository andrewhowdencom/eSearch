<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

// phpcs:ignore -- Doesn't need to be in a namespace, as it's a task runner.
class RoboFile extends \Robo\Tasks
{
    const DEV_PRESENT = 'present';
    const DEV_ABSENT  = 'absent';

    /**
     * Starts the developer environment
     *
     * @option state Whether the development environment should be present (up) or absent (down, halted)
     */
    public function dev($opts = ['state' => self::DEV_PRESENT])
    {
        $validConditions = [self::DEV_PRESENT, self::DEV_ABSENT];

        // Validity checking
        if (!in_array($opts['state'], $validConditions)) {
            throw new Exception(
                sprintf("state should be one of %s, not '%s'", implode(',', $validConditions), $opts['state'])
            );
        }

        if ($opts['state'] === self::DEV_ABSENT) {
            $subCommands = ['rm', '--stop', '--force'];
        } else {
            $subCommands = ['up', '--build', '--detach'];
        }

        $this->taskExec('docker-compose ' . implode(' ', $subCommands))
            ->dir('deploy/docker-compose')
            ->run();
    }
}
