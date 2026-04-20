# FSMProject integration into Worksuite project view

To show FSM Orders inside the native Worksuite project page, include the panel partial in the project overview view.

## Include point

File: `/resources/views/projects/show.blade.php`

The `@include($view)` call renders the current project tab content. Add the FSM panel include in the project overview tab view (the blade resolved by `$view` when `tab=overview`) where project-related panels are rendered.

```blade
@include('fsmproject::orders.panel', [
    'project' => $project,
    'orders' => $project->fsmOrders()->with(['stage', 'person'])->latest()->get(),
])
```

If you only want linked orders from the active tenant context, keep the same include point and add your existing project/company scope to the orders query.
