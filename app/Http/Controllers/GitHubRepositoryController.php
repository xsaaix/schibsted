<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\GitHubService;

class GitHubRepositoryController extends Controller
{
    /**
     * @var App\Services\GitHubRepositoryComparisonService
     */
    protected $gitHubService;

    public function __construct(GitHubService $service)
    {
        $this->gitHubService = $service;
    }

    public function compareRepositoriesByNames(string $name1, string $name2):JsonResponse
    {
        $repo1 = $this->gitHubService->findRepository($name1);
        $repo2 = $this->gitHubService->findRepository($name2);

        if (in_array(0, [$repo1['total_count'], $repo2['total_count']]) === false) {
            $comparison = $this->gitHubService->compareRepositories([$repo1['items'][0], $repo2['items'][0]]);
        }

        return response()->json($comparison);
    }

    public function compareRepositoriesByUsersAndNames(string $user1, string $user2, string $name1, string $name2):JsonResponse
    {
        $repo1 = $this->gitHubService->getRepository($user1, $name1);
        $repo2 = $this->gitHubService->getRepository($user2, $name2);

        $comparison = $this->gitHubService->compareRepositories([$repo1, $repo2]);

        return response()->json($comparison);
    }

    public function compareRepositoriesByURLs(Request $request):JsonResponse
    {
        $validated = $request->validate([
            'url1' => 'required|url',
            'url2' => 'required|url'
        ]);

        $repo1 = rawurldecode($validated['url1']);
        $repo2 = rawurldecode($validated['url2']);
        $repo1 = array_reverse(explode('/', $repo1));
        $repo2 = array_reverse(explode('/', $repo2));

        $repo1 = $this->gitHubService->getRepository($repo1[1], $repo1[0]);
        $repo2 = $this->gitHubService->getRepository($repo2[1], $repo2[0]);

        $comparison = $this->gitHubService->compareRepositories([$repo1, $repo2]);

        return response()->json($comparison);
    }
}
