/// Local-state model for checklist execution flow.
/// Items are loaded from GET /api/nexus/v1/jobs/{id}/checklist and submitted
/// to POST /api/nexus/v1/jobs/{id}/checklist on completion.

enum ChecklistState {
  pending,
  active,
  paused,
  completed,
  verified,
}

extension ChecklistStateX on ChecklistState {
  String get label {
    switch (this) {
      case ChecklistState.pending:
        return 'checklist_state_pending';
      case ChecklistState.active:
        return 'checklist_state_active';
      case ChecklistState.paused:
        return 'checklist_state_paused';
      case ChecklistState.completed:
        return 'checklist_state_completed';
      case ChecklistState.verified:
        return 'checklist_state_verified';
    }
  }

  bool get canStart =>
      this == ChecklistState.pending || this == ChecklistState.paused;
  bool get canPause => this == ChecklistState.active;
  bool get canComplete => this == ChecklistState.active;
  bool get isFinished =>
      this == ChecklistState.completed || this == ChecklistState.verified;
}

class ChecklistExecutionModel {
  final String jobId;
  ChecklistState state;
  DateTime? startedAt;
  DateTime? completedAt;

  /// Items loaded from the Nexus checklist API.
  /// Each map: { 'id': String, 'title': String, 'required': bool, 'is_complete': bool }
  List<Map<String, dynamic>> items = [];

  ChecklistExecutionModel({
    required this.jobId,
    this.state = ChecklistState.pending,
    this.startedAt,
    this.completedAt,
  });

  /// Load checklist items from the API response and initialise completion flags.
  void loadRemoteItems(List<Map<String, dynamic>> remoteItems) {
    items = remoteItems.map((item) => {
      'id':          item['id']?.toString() ?? '',
      'title':       item['title']?.toString() ?? '',
      'required':    item['required'] == true,
      'is_complete': false,
    }).toList();
  }

  void toggleItem(int index) {
    if (index < items.length) {
      items[index]['is_complete'] = !(items[index]['is_complete'] as bool? ?? false);
    }
  }

  void start() {
    if (state.canStart) {
      state = ChecklistState.active;
      startedAt ??= DateTime.now();
    }
  }

  void pause() {
    if (state.canPause) {
      state = ChecklistState.paused;
    }
  }

  void complete() {
    if (state.canComplete) {
      state = ChecklistState.completed;
      completedAt = DateTime.now();
    }
  }
}


enum ChecklistState {
  pending,
  active,
  paused,
  completed,
  verified,
}

extension ChecklistStateX on ChecklistState {
  String get label {
    switch (this) {
      case ChecklistState.pending:
        return 'checklist_state_pending';
      case ChecklistState.active:
        return 'checklist_state_active';
      case ChecklistState.paused:
        return 'checklist_state_paused';
      case ChecklistState.completed:
        return 'checklist_state_completed';
      case ChecklistState.verified:
        return 'checklist_state_verified';
    }
  }

  bool get canStart =>
      this == ChecklistState.pending || this == ChecklistState.paused;
  bool get canPause => this == ChecklistState.active;
  bool get canComplete => this == ChecklistState.active;
  bool get isFinished =>
      this == ChecklistState.completed || this == ChecklistState.verified;
}

class ChecklistExecutionModel {
  final String jobId;
  ChecklistState state;
  DateTime? startedAt;
  DateTime? completedAt;

  ChecklistExecutionModel({
    required this.jobId,
    this.state = ChecklistState.pending,
    this.startedAt,
    this.completedAt,
  });

  void start() {
    if (state.canStart) {
      state = ChecklistState.active;
      startedAt ??= DateTime.now();
    }
  }

  void pause() {
    if (state.canPause) {
      state = ChecklistState.paused;
    }
  }

  void complete() {
    if (state.canComplete) {
      state = ChecklistState.completed;
      completedAt = DateTime.now();
    }
  }
}
