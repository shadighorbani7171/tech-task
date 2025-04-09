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
 * This controller handles all user management tasks.
 * It uses UserService to work with user data.
 * It provides API endpoints for users.
 */
class UserController extends Controller
{
    /**
     * The user service that does the actual work
     */
    private UserService $userService;

    /**
     * Create a new controller
     * 
     * @param UserService $userService The service that manages users
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get a list of all users
     * 
     * This method returns users with pagination.
     * You can set how many users per page with 'per_page' parameter.
     * 
     * @param Request $request The HTTP request with options
     * @return JsonResponse JSON with user list and pagination data
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Get how many users per page (default: 10)
            $perPage = $request->get('per_page', 10);
            
            // Get paginated users from service
            $users = $this->userService->getAllPaginated($perPage);

            // Return success response with users
            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        } catch (\Exception $e) {
            // Handle errors and return error message
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a new user
     * 
     * This method creates a new user with validated data.
     * It uses CreateUserRequest to validate input.
     * 
     * @param CreateUserRequest $request The validated HTTP request 
     * @return JsonResponse JSON with result and new user data
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            // Create new user with validated data
            $user = $this->userService->createUser($request->validated());

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $user
            ], Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            // Handle bad input errors
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            // Handle all other errors
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show one user's details
     * 
     * This method gets information about one user by ID.
     * If the user is not found, it returns an error.
     * 
     * @param int $id The ID of the user
     * @return JsonResponse JSON with user data or error
     */
    public function show(int $id): JsonResponse
    {
        try {
            // Find user by ID
            $user = $this->userService->findById($id);
            
            // Check if user exists
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], Response::HTTP_NOT_FOUND);
            }

            // Return user data
            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update a user's information
     * 
     * This method updates an existing user with new data.
     * First it finds the user, then updates their data.
     * 
     * @param UpdateUserRequest $request The validated HTTP request
     * @param int $id The ID of the user to update
     * @return JsonResponse JSON with result and updated user data
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            // Find user by ID
            $user = $this->userService->findById($id);
            
            // Check if user exists
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], Response::HTTP_NOT_FOUND);
            }

            // Update user data
            $this->userService->updateUser($user, $request->validated());

            // Return updated user data
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => $user
            ]);
        } catch (\InvalidArgumentException $e) {
            // Handle bad input errors
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            // Handle all other errors
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a user
     * 
     * This method removes a user from the system.
     * It first checks if the user exists, then deletes them.
     * 
     * @param int $id The ID of the user to delete
     * @return JsonResponse JSON with result of the operation
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            // Find user by ID
            $user = $this->userService->findById($id);
            
            // Check if user exists
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], Response::HTTP_NOT_FOUND);
            }

            // Delete the user
            $this->userService->deleteUser($user);

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get list of all countries
     * 
     * This method returns a list of countries that users can select.
     * 
     * @return JsonResponse JSON with countries list
     */
    public function countries(): JsonResponse
    {
        try {
            // Get list of countries
            $countries = $this->userService->getCountryList();

            // Return countries data
            return response()->json([
                'status' => 'success',
                'data' => $countries
            ]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve countries',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
} 