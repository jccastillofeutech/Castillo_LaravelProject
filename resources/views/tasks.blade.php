<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage your tasks in one place.">
    <title>My Tasks</title>
    @vite('resources/css/tasks.css')
</head>

<body>
    <main class="dashboard-shell">
        <header class="dashboard-header">
            <a class="brand" href="{{ route('tasks.index') }}">
                <span class="brand-mark">S</span>
                <span>Student Management System</span>
            </a>
            <span class="header-note">Task workspace</span>
        </header>

        <section class="hero" aria-labelledby="page-title">
            <div class="hero-content">
                <p class="eyebrow">Personal dashboard</p>
                <h1 id="page-title">My Tasks</h1>
                <p class="hero-copy">Plan clearly, work steadily, and keep your priorities moving forward.</p>
            </div>
            <div class="hero-accent" aria-hidden="true"></div>
        </section>

        @if (session('success'))
            <div class="alert" role="alert">{{ session('success') }}</div>
        @endif

        <section class="stats-grid" aria-label="Task summary">
            <div class="stat-card">
                <span class="stat-label">Total tasks</span>
                <strong>{{ $tasks->count() }}</strong>
                <span class="stat-detail">All saved tasks</span>
            </div>
            <div class="stat-card stat-card-blue">
                <span class="stat-label">In progress</span>
                <strong>{{ $tasks->where('completed', false)->count() }}</strong>
                <span class="stat-detail">Ready for attention</span>
            </div>
            <div class="stat-card stat-card-dark">
                <span class="stat-label">Completed</span>
                <strong>{{ $tasks->where('completed', true)->count() }}</strong>
                <span class="stat-detail">Work finished</span>
            </div>
        </section>

        <div class="task-layout">
            <section class="panel form-panel" aria-labelledby="add-task-heading">
                <div class="panel-heading">
                    <span class="panel-icon">+</span>
                    <div>
                        <h2 class="panel-title" id="add-task-heading">Add a task</h2>
                        <p class="panel-description">Start with one clear next step.</p>
                    </div>
                </div>

                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label for="task_title">Task title</label>
                        <input class="@error('task_title') is-invalid @enderror" type="text" id="task_title"
                            name="task_title" value="{{ old('task_title') }}" placeholder="e.g. Review project brief"
                            required>
                        @error('task_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="description">Description <span class="field-hint">(optional)</span></label>
                        <textarea class="@error('description') is-invalid @enderror" id="description" name="description"
                            rows="5" placeholder="Add useful details about this task">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="primary-button" type="submit">Create task <span aria-hidden="true">→</span></button>
                </form>
            </section>

            <section class="saved-section" aria-labelledby="saved-tasks-heading">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">Your list</p>
                        <h2 id="saved-tasks-heading">Saved tasks</h2>
                    </div>
                    <span class="section-note">Latest first</span>
                </div>

                @forelse ($tasks as $task)
                    <article class="task-card">
                        <div class="task-card-marker" aria-hidden="true"></div>
                        <div class="task-card-content">
                            <div class="task-card-heading">
                                <h3>{{ $task->task_title }}</h3>
                                <span class="status {{ $task->completed ? 'completed' : '' }}">
                                    {{ $task->completed ? 'Completed' : 'In progress' }}
                                </span>
                            </div>
                            <p>{{ $task->description ?: 'No description provided.' }}</p>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <span class="empty-icon">+</span>
                        <h3>No tasks yet</h3>
                        <p>Add your first task using the form to get started.</p>
                    </div>
                @endforelse
            </section>
        </div>
    </main>
</body>

</html>
