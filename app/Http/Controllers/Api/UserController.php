<?php

namespace App\Http\Controllers\Api;

use App\Domain\User\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User Controller
 * 
 * Handles all user management tasks
 * Uses UserService for data operations
 * Provides API endpoints for users
 */
class UserController extends Controller
{
    /**
     * The service that handles user operations
     */
    private UserService $userService;

    /**
     * Create new controller instance
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get list of users
     * Uses pagination
     * Default is 10 users per page
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $users = $this->userService->getAllPaginated($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create new user
     * Validates input data
     * Returns created user data
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $user
            ], Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get user details by ID
     * Returns 404 if user not found
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userService->findById($id);
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update user information
     * Validates input data
     * Returns updated user data
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $user = $this->userService->findById($id);
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $this->userService->updateUser($user, $request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => $user
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a user
     * Returns 404 if user not found
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = $this->userService->findById($id);
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $this->userService->deleteUser($user);

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get list of available countries
     */
    public function countries(): JsonResponse
    {
        try {
            $countries = $this->userService->getCountryList();

            return response()->json([
                'status' => 'success',
                'data' => $countries
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve countries',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
} 