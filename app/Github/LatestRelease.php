<?php

namespace REBELinBLUE\Deployer\Github;

use GuzzleHttp\Client;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use REBELinBLUE\Deployer\Contracts\Github\LatestReleaseInterface;

/**
 * A class to get the latest release tag for Github.
 */
class LatestRelease implements LatestReleaseInterface
{
    const CACHE_TIME_IN_HOURS = 12;

    /**
     * @var string
     **/
    private $github_url = 'https://api.github.com/repos/REBELinBLUE/deployer/releases/latest';

    /**
     * @var CacheRepository
     */
    private $cache;

    /**
     * LatestRelease constructor.
     *
     * @param CacheRepository $cache
     */
    public function __construct(CacheRepository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get the latest release from Github.
     *
     * @return false|string
     */
    public function latest()
    {
        $cache_for = self::CACHE_TIME_IN_HOURS * 60;

        $release = $this->cache->remember('latest_version', $cache_for, function () {
            $headers = [
                'Accept'     => 'application/vnd.github.v3+json',
                'User-Agent' => USER_AGENT,
            ];

            if (config('deployer.github_oauth_token')) {
                $headers['OAUTH-TOKEN'] = config('deployer.github_oauth_token');
            }

            try {
                $response = (new Client(['timeout'  => 5]))->get($this->github_url, [
                    'headers' => $headers,
                ]);
            } catch (\Exception $exception) {
                return false;
            }

            return json_decode($response->getBody());
        });

        if (is_object($release)) {
            return $release->tag_name;
        }

        return false;
    }
}
