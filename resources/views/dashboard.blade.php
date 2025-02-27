@extends('layouts.admin_master')
@section('admin')
<style>
    .kanban-container {
        display: flex;
        gap: 20px;
        justify-content: center;
        padding: 20px;
    }

    .kanban-column {
        width: 30%;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .kanban-column h4 {
        text-align: center;
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }

    .kanban-list {
        min-height: 200px;
        padding: 10px;
        background: #ffffff;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .kanban-item {
        background: #4a90e2;
        /* Softer blue */
        color: white;
        padding: 12px;
        margin-bottom: 12px;
        border-radius: 8px;
        cursor: grab;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    .kanban-item:hover {
        transform: scale(1.03);
    }

    .task-desc {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-top: 5px;
    }
</style>
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#calendar" role="tab">
                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                            <span class="d-none d-sm-block">Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#task" role="tab">
                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                            <span class="d-none d-sm-block">Task</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="calendar" role="tabpanel">
                        <div id="sampleCalendar"></div>
                    </div>
                    <div class="tab-pane" id="task" role="tabpanel">
                        <div class="kanban-container">
                            <div class="kanban-column">
                                <h4>To Do</h4>
                                <div class="kanban-list" id="pending-task"></div>
                            </div>
                            <div class="kanban-column">
                                <h4>In Progress</h4>
                                <div class="kanban-list" id="cancelled-task"></div>
                            </div>
                            <div class="kanban-column">
                                <h4>Complete</h4>
                                <div class="kanban-list" id="complete-task"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        var calendarEvents = []; // Declare calendarEvents as global variable
        getScheduledActivity();
        getKanbanTasks();

        function getScheduledActivity() {
            $.ajax({
                url: "{{ route('get-scheduled-activity') }}",
                method: "GET",
                success: function(data) {


                    // Build the calendarEvents array
                    calendarEvents = data.data.map(event => {
                        var eventName = event.status + ' ' + 'task status';
                        return {
                            id: event.id,
                            name: event.name,
                            date: event.date,
                            description: event.description, // Event description (optional)
                            type: event.type,
                            everyYear: false,
                            color: event.color,
                        };
                    });
                    // // Call the function to initialize the calendar after data is received and processed
                    initializeCalendar(calendarEvents);
                },
                error: function(err) {

                    console.log(err);
                }
            });
        }


        function getKanbanTasks() {
            $.ajax({
                url: "{{ route('scheduled-activity-kanban') }}",
                method: "GET",
                success: function(data) {
                    console.log(data);

                    $(".kanban-list").empty(); // Clear lists before adding items

                    data.data.forEach(task => {
                        let taskElement = `
        <div class="kanban-item" data-id="${task.id}">
            <strong>${task.name}</strong>
            <p class="task-desc">${task.description || 'No description available'}</p>
        </div>`;
                        $("#" + task.status.toLowerCase() + "-task").append(taskElement);
                    });

                    initializeDragula();
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        function initializeCalendar(calendarEvents) {
            $("#sampleCalendar").evoCalendar({
                language: 'en',
                theme: 'Royal Navy',
                getActiveEvent: true,
                todayHighlight: true,
                calendarEvents: calendarEvents, // Use the dynamically built array
            });
        }

        function initializeDragula() {
            dragula([
                document.getElementById("pending-task"),
                document.getElementById("cancelled-task"),
                document.getElementById("complete-task")
            ]).on("drop", function(el, target) {


                let taskId = $(el).data("id"); // Get task ID from data attribute
                let newStatus = target.id.replace("-task", ""); // Extract status from ID

                // Send AJAX request to update status
                updateTaskStatus(taskId, newStatus);
            });
        }

        function updateTaskStatus(taskId, newStatus) {
            $.ajax({
                url: "{{ route('update-task-kanban') }}", // Define this route in Laravel
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}", // CSRF protection
                    id: taskId,
                    status: newStatus
                },
                success: function(response) {
                    console.log("Task updated successfully", response);
                },
                error: function(error) {
                    console.log("Error updating task", error);
                }
            });
        }


    });
</script>
<!-- Include kanban.init.js -->
<script src="{{ asset('backend/assets/js/pages/kanban.init.js') }}"></script>
@endsection