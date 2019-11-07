<?php

namespace App\Services;

use GrahamCampbell\GitHub\GitHubManager;

class GitHubService
{
    /**
     * @var string
     */
    protected $connection;

    /**
     * @var GrahamCampbell\GitHub\GitHubManager
     */
    protected $gitHub;

    public function __construct(GitHubManager $gitHub)
    {
        $this->connection = env('GITHUB_DEFAULT_CONNECTION', 'none');
        $this->gitHub = $gitHub;
        $this->gitHub->setDefaultConnection($this->connection);
    }

    /**
     * Compares multiple repositories
     *
     * @param  array  $repositories
     * @return null|array
     */
    public function compareRepositories(array $repositories):?array
    {
        $compared = [];
        foreach ($repositories as $key => $repo) {
            if (empty($repo) === false) $info[$key] = $this->extractInfo($repo);
        }

        $comparison = $this->compareInfo($info);
        $info['comparison'] = $comparison;

        return $info;
    }

    /**
     * Extracts info from a repository array
     *
     * @param  array  $repository
     * @return null|array
     */
    public function extractInfo(array $repository):?array
    {
        $mapper = [
            'name',
            'owner',
            'forks_count',
            'stargazers_count',
            'watchers_count',
        ];

        $username = '';
        $name = '';

        $info = (array_intersect_key($repository, array_flip($mapper)));

        if (array_key_exists('owner', $info) === true) {
            $username = $info['owner'] = array_key_exists('login', $info['owner']) === true ? $info['owner']['login'] : '';
        }

        $name = array_key_exists('name', $info) === true ? $info['name'] : '';

        $lastRelease = $this->gitHub->repo()->releases()->all($username, $name);
        $info['last_release_date'] = empty($lastRelease) === false ? $lastRelease[0]['published_at'] : null;

        $openPRs = $this->gitHub->pullRequests()->all($username, $name, ['state' => 'open']);
        $closedPRs = $this->gitHub->pullRequests()->all($username, $name, ['state' => 'closed']);
        $info['open_pull_requests_count'] = count($openPRs);
        $info['closed_pull_requests_count'] = count($closedPRs);

        return $info;
    }

    /**
     * Compares info
     *
     * @param  array  $info
     * @return array
     */
    public function compareInfo(array $info):array
    {
        $comparison = [];

        foreach (array_keys($info[0]) as $key) {
            if (in_array($key, ['owner', 'name']) == true) continue;
            $comparison[$key] = array_column($info, $key);
        }

        // as I'm unsure whether I should enforce business logic
        // so I will keep this commented - will work when uncommented
        // $comparison['best'] = $this->enforceCriteria($comparison);

        return $comparison;
    }

    /**
     * Enforces business logic criteria of judging the data
     *
     * @param  array  $comparison
     * @return array
     */
    protected function enforceCriteria(array $comparison):array
    {
        $result = [];
        foreach ($comparison as $key => $comp) {
            if (in_array($key, ['owner', 'name']) == true) {
                continue;
            } elseif ($key !== 'open_pull_requests_count') {
                $result[$key] = array_keys($comp, max($comp));
            } else {
                $result[$key] = array_keys($comp, min($comp));
            }
        }
        return $result;
    }

    /**
     * Fetches repository or aborts with an error message
     *
     * @param  string $username
     * @param  string $name
     * @return array
     */
    public function getRepository(string $username, string $name):array
    {
        try {
            return $this->gitHub->repo()->show($username, $name);
        } catch(\Exception $e) {
            abort($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Searches GitHub for a repository
     *
     * @param  string $name
     * @param  string $sort
     * @return array
     */
    public function findRepository(string $name, string $sort = 'none'):array
    {
        return $this->gitHub->search()->repositories($name, $sort);
    }
}
