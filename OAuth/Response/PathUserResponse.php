<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware.Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HWI\Bundle\OAuthBundle\OAuth\Response;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class parsing the properties by given path options.
 *
 * @author Geoffrey Bachelet <geoffrey.bachelet@gmail.com>
 * @author Alexander <iam.asm89@gmail.com>
 */
class PathUserResponse extends AbstractUserResponse
{
    protected $paths;

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->getValueForPath('username_path');
    }

    /**
     * Get the name to display.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->getValueForPath('displayname_path');
    }

    public function getPaths()
    {
        return $this->paths;
    }

    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    protected function getValueForPath($path)
    {
        $steps = explode('.', $this->paths[$path]);

        $value = $this->response;
        foreach ($steps as $step) {
            if (!array_key_exists($step, $value)) {
                throw new AuthenticationException(sprintf('Could not follow path "%s" in OAuth provider response: %s', $this->paths[$path], var_export($this->response, true)));
            }
            $value = $value[$step];
        }

        return $value;
    }
}