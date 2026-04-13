class TodayModel {
  final Map<String, dynamic> worker;
  final List<VisitSummary>   todayVisits;
  final VisitSummary?        nextVisit;
  final int                  openIssues;
  final Map<String, int>     stageCounts;

  const TodayModel({
    required this.worker,
    required this.todayVisits,
    this.nextVisit,
    required this.openIssues,
    required this.stageCounts,
  });

  factory TodayModel.fromJson(Map<String, dynamic> j) => TodayModel(
        worker:      (j['worker'] as Map<String, dynamic>?) ?? {},
        todayVisits: ((j['today_visits'] as List?) ?? [])
            .map((e) => VisitSummary.fromJson(e as Map<String, dynamic>))
            .toList(),
        nextVisit:   j['next_visit'] != null
            ? VisitSummary.fromJson(j['next_visit'] as Map<String, dynamic>)
            : null,
        openIssues: (j['open_issues'] as num?)?.toInt() ?? 0,
        stageCounts: ((j['stage_counts'] as Map?) ?? {})
            .map((k, v) => MapEntry(k.toString(), (v as num).toInt())),
      );
}

class VisitSummary {
  final int     id;
  final String  name;
  final String? stage;
  final bool    isComplete;
  final String? scheduledStart;
  final String? scheduledEnd;
  final String? actualStart;
  final String? actualEnd;
  final LocationSummary? location;
  final String  priority;

  const VisitSummary({
    required this.id,
    required this.name,
    this.stage,
    required this.isComplete,
    this.scheduledStart,
    this.scheduledEnd,
    this.actualStart,
    this.actualEnd,
    this.location,
    required this.priority,
  });

  factory VisitSummary.fromJson(Map<String, dynamic> j) => VisitSummary(
        id:             (j['id'] as num).toInt(),
        name:           j['name']?.toString() ?? '',
        stage:          j['stage']?.toString(),
        isComplete:     j['is_complete'] == true,
        scheduledStart: j['scheduled_start']?.toString(),
        scheduledEnd:   j['scheduled_end']?.toString(),
        actualStart:    j['actual_start']?.toString(),
        actualEnd:      j['actual_end']?.toString(),
        location: j['location'] != null
            ? LocationSummary.fromJson(j['location'] as Map<String, dynamic>)
            : null,
        priority: j['priority']?.toString() ?? '0',
      );

  bool get isUrgent    => priority == '1';
  bool get isCheckedIn => actualStart != null;
  bool get isCheckedOut => actualEnd != null;
}

class LocationSummary {
  final int     id;
  final String  name;
  final String? street;
  final String? city;
  final String? state;
  final String? zip;
  final double? latitude;
  final double? longitude;
  final String? notes;

  const LocationSummary({
    required this.id,
    required this.name,
    this.street,
    this.city,
    this.state,
    this.zip,
    this.latitude,
    this.longitude,
    this.notes,
  });

  factory LocationSummary.fromJson(Map<String, dynamic> j) => LocationSummary(
        id:        (j['id'] as num).toInt(),
        name:      j['name']?.toString() ?? '',
        street:    j['street']?.toString(),
        city:      j['city']?.toString(),
        state:     j['state']?.toString(),
        zip:       j['zip']?.toString(),
        latitude:  (j['latitude'] as num?)?.toDouble(),
        longitude: (j['longitude'] as num?)?.toDouble(),
        notes:     j['notes']?.toString(),
      );

  String get fullAddress => [street, city, state, zip]
      .where((s) => s != null && s.isNotEmpty)
      .join(', ');
}
