<?php

namespace Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandler\Oidc;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Discovery'.\DIRECTORY_SEPARATOR.'CacheConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class DiscoveryConfig 
{
    private $baseUri;
    private $cache;
    private $_usedProperties = [];

    /**
     * Base URI of the OIDC server.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function baseUri($value): static
    {
        $this->_usedProperties['baseUri'] = true;
        $this->baseUri = $value;

        return $this;
    }

    public function cache(array $value = []): \Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandler\Oidc\Discovery\CacheConfig
    {
        if (null === $this->cache) {
            $this->_usedProperties['cache'] = true;
            $this->cache = new \Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandler\Oidc\Discovery\CacheConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "cache()" has already been initialized. You cannot pass values the second time you call cache().');
        }

        return $this->cache;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('base_uri', $value)) {
            $this->_usedProperties['baseUri'] = true;
            $this->baseUri = $value['base_uri'];
            unset($value['base_uri']);
        }

        if (array_key_exists('cache', $value)) {
            $this->_usedProperties['cache'] = true;
            $this->cache = new \Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandler\Oidc\Discovery\CacheConfig($value['cache']);
            unset($value['cache']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['baseUri'])) {
            $output['base_uri'] = $this->baseUri;
        }
        if (isset($this->_usedProperties['cache'])) {
            $output['cache'] = $this->cache->toArray();
        }

        return $output;
    }

}
