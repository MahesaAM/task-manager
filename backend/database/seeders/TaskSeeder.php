<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        // Create tasks for each user (tasks they created)
        foreach ($users as $user) {
            // Create 5-10 tasks per user
            $taskCount = rand(5, 10);

            for ($i = 1; $i <= $taskCount; $i++) {
                $randomAssignee = rand(0, 1) ? $users->random() : null;

                $statuses = ['todo', 'in-progress', 'done'];
                $priorities = ['low', 'medium', 'high'];

                $taskTitles = [
                    'Implement user authentication system',
                    'Create task management API endpoints',
                    'Design database schema for tasks',
                    'Build file upload functionality',
                    'Develop frontend dashboard interface',
                    'Write comprehensive unit tests',
                    'Setup CI/CD deployment pipeline',
                    'Optimize database query performance',
                    'Conduct security vulnerability audit',
                    'Create user documentation and guides',
                    'Implement real-time notifications',
                    'Add task filtering and search features',
                    'Create user role management system',
                    'Build task assignment workflow',
                    'Implement task status tracking',
                    'Add task priority management',
                    'Create task deadline reminders',
                    'Build task attachment system',
                    'Implement task commenting feature',
                    'Add task history and audit logs',
                    'Create task reporting dashboard',
                    'Build task export functionality',
                    'Implement task bulk operations',
                    'Add task template system',
                    'Create task dependency management',
                    'Build task time tracking',
                    'Implement task collaboration features',
                    'Add task progress indicators',
                    'Create task milestone tracking',
                    'Build task calendar integration',
                    'Implement task email notifications',
                    'Add task mobile responsiveness',
                    'Create task data backup system',
                    'Build task analytics dashboard',
                    'Implement task archiving system',
                    'Add task version control',
                    'Create task approval workflow',
                    'Build task sharing capabilities',
                    'Implement task tagging system',
                    'Add task custom fields',
                    'Create task automation rules',
                    'Build task integration with external tools',
                    'Implement task data import/export',
                    'Add task deadline extensions',
                    'Create task performance metrics',
                    'Build task user feedback system',
                    'Implement task accessibility features',
                    'Add task dark mode support',
                    'Create task multi-language support',
                    'Build task offline functionality',
                    'Implement task conflict resolution',
                    'Add task progress visualization'
                ];

                $taskDescriptions = [
                    'This task involves setting up a secure authentication system for user access control.',
                    'Building RESTful API endpoints to handle task CRUD operations efficiently.',
                    'Designing and implementing a robust database schema for task management.',
                    'Developing file upload capabilities with proper validation and storage.',
                    'Creating an intuitive dashboard interface for task management operations.',
                    'Writing comprehensive test suites to ensure code quality and reliability.',
                    'Setting up automated deployment pipelines for continuous integration.',
                    'Optimizing database queries to improve application performance.',
                    'Conducting thorough security audits to identify and fix vulnerabilities.',
                    'Creating detailed documentation for users and developers.',
                    'Implementing real-time notification system for task updates.',
                    'Adding advanced filtering and search capabilities to task lists.',
                    'Developing a role-based access control system for users.',
                    'Building workflow for efficient task assignment and management.',
                    'Implementing comprehensive status tracking for task lifecycle.',
                    'Adding priority management system for task organization.',
                    'Creating reminder system for approaching task deadlines.',
                    'Building secure file attachment system for tasks.',
                    'Implementing commenting feature for task collaboration.',
                    'Adding audit logs to track task history and changes.',
                    'Creating reporting dashboard for task analytics and insights.',
                    'Building functionality to export task data in various formats.',
                    'Implementing bulk operations for efficient task management.',
                    'Creating template system for common task types.',
                    'Building dependency management for related tasks.',
                    'Implementing time tracking for task duration monitoring.',
                    'Adding collaboration features for team task management.',
                    'Creating progress indicators for task completion tracking.',
                    'Building milestone tracking for project management.',
                    'Implementing calendar integration for task scheduling.',
                    'Adding email notification system for task updates.',
                    'Ensuring mobile responsiveness for all devices.',
                    'Creating automated backup system for task data.',
                    'Building analytics dashboard for task performance metrics.',
                    'Implementing archiving system for completed tasks.',
                    'Adding version control for task content changes.',
                    'Creating approval workflow for task validation.',
                    'Building sharing capabilities for task collaboration.',
                    'Implementing tagging system for task categorization.',
                    'Adding custom fields for flexible task data.',
                    'Creating automation rules for task management.',
                    'Building integration with external productivity tools.',
                    'Implementing data import/export functionality.',
                    'Adding deadline extension capabilities.',
                    'Creating performance metrics for task analysis.',
                    'Building user feedback system for improvements.',
                    'Implementing accessibility features for all users.',
                    'Adding dark mode support for user preference.',
                    'Creating multi-language support for global users.',
                    'Building offline functionality for network independence.',
                    'Implementing conflict resolution for concurrent edits.',
                    'Adding visualization for task progress tracking.'
                ];

                Task::create([
                    'title' => $taskTitles[($i - 1) % count($taskTitles)],
                    'description' => $taskDescriptions[($i - 1) % count($taskDescriptions)],
                    'status' => $statuses[array_rand($statuses)],
                    'priority' => $priorities[array_rand($priorities)],
                    'assigned_user_id' => $randomAssignee?->id,
                    'created_by' => $user->id,
                    'due_date' => rand(0, 1) ? now()->addDays(rand(1, 90)) : null,
                ]);
            }
        }
    }
}
