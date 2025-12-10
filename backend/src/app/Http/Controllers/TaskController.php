<?php

namespace App\Http\Controllers;

use App\Events\TaskCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function getFilters(): JsonResponse
    {
        $users = User::select('id', 'name')->get();
        $statuses = TaskStatus::all();

        return response()->json([
            'success' => true,
            'users' => $users,
            'statuses' => $statuses
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $query = Task::with(['status', 'assignee']);

        if ($request->has('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->has('user_id')) {
            $query->where('assigned_id', $request->user_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('completion_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('completion_date', '<=', $request->date_to);
        }

        $tasks = $query->get();

        return response()->json([
            'success' => true,
            'data' => $tasks,
        ]);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $task = Task::create($data);

            if ($request->hasFile('attachment')) {
                $task->addMedia($request->file('attachment'))
                    ->toMediaCollection('attachments');
            }

            DB::commit();

            TaskCreatedEvent::dispatch($task);

            return response()->json([
                'success' => true,
                'message' => 'Задача успешно создана',
                'task' => $task->load(['status', 'assignee'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании задачи',
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Задача не найдена'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }

    public function update(UpdateTaskRequest $request, $id): JsonResponse
    {
        $task = Task::with(['assignee', 'status'])->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Задача не найдена'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->validated();
            $task->update($data);

            if ($request->hasFile('attachment')) {
                $task->clearMediaCollection('attachments');
                $task->addMedia($request->file('attachment'))
                    ->toMediaCollection('attachments');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Задача успешно обновлена',
                'task' => $task->load(['assignee', 'status'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении задачи',
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Задача не найдена'
            ], 422);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Задача успешно удалена'
        ]);
    }
}