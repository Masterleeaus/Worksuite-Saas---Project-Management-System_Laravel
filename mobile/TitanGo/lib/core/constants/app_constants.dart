/// Central constants for Titan Go.
///
/// Terminology doctrine:
///   provider  → company
///   serviceman → worker
///   booking   → job
///   order     → visit
class AppConstants {
  AppConstants._();

  static const String appName    = 'Titan Go';
  static const String appVersion = '1.0.0';

  // ──────────────────────────────────────────────────────────────────────────
  // Base URL (set at build/runtime via --dart-define or remote config)
  // ──────────────────────────────────────────────────────────────────────────
  static const String baseUrl = String.fromEnvironment(
    'TITAN_GO_BASE_URL',
    defaultValue: 'https://YOUR_WORKSUITE_DOMAIN',
  );

  // ──────────────────────────────────────────────────────────────────────────
  // FSMCore endpoints (worker auth + order lifecycle)
  // ──────────────────────────────────────────────────────────────────────────
  static const String loginUri        = '/api/fsm/v1/auth/login';
  static const String logoutUri       = '/api/fsm/v1/auth/logout';
  static const String meUri           = '/api/fsm/v1/auth/me';
  static const String jobsUri         = '/api/fsm/v1/orders';
  static const String checkInUri      = '/api/fsm/v1/orders/{id}/checkin';
  static const String checkOutUri     = '/api/fsm/v1/orders/{id}/checkout';
  static const String completeUri     = '/api/fsm/v1/orders/{id}/complete';
  static const String stageUri        = '/api/fsm/v1/orders/{id}/stage';
  static const String photosUri       = '/api/fsm/v1/orders/{id}/photos';
  static const String timesheetsUri   = '/api/fsm/v1/timesheets';

  // ──────────────────────────────────────────────────────────────────────────
  // Nexus Field Ops endpoints (Titan Go extensions)
  // ──────────────────────────────────────────────────────────────────────────
  static const String dashboardUri    = '/api/nexus/v1/dashboard';
  static const String workerMeUri     = '/api/nexus/v1/worker/me';
  static const String fcmTokenUri     = '/api/nexus/v1/worker/fcm-token';
  static const String workerStatusUri = '/api/nexus/v1/worker/status';
  static const String locationPingUri = '/api/nexus/v1/worker/location';
  static const String notesUri        = '/api/nexus/v1/jobs/{id}/notes';
  static const String issuesUri       = '/api/nexus/v1/jobs/{id}/issues';
  static const String checklistUri    = '/api/nexus/v1/jobs/{id}/checklist';
  static const String syncUri         = '/api/nexus/v1/sync';

  // ──────────────────────────────────────────────────────────────────────────
  // SharedPreferences / SecureStorage keys
  // ──────────────────────────────────────────────────────────────────────────
  static const String keyToken      = 'titan_go_token';
  static const String keyWorkerId   = 'titan_go_worker_id';
  static const String keyCompanyId  = 'titan_go_company_id';
  static const String keyDeviceId   = 'titan_go_device_id';
  static const String keyWorkerName = 'titan_go_worker_name';

  // ──────────────────────────────────────────────────────────────────────────
  // FCM topics (tenant-scoped)
  // ──────────────────────────────────────────────────────────────────────────
  static String companyTopic(int companyId)  => 'company_${companyId}_workers';
  static String workerTopic(int workerId)    => 'worker_$workerId';

  // ──────────────────────────────────────────────────────────────────────────
  // GPS tracking intervals (seconds)
  // ──────────────────────────────────────────────────────────────────────────
  static const int gpsForegroundIntervalSec  = 15;
  static const int gpsBackgroundIntervalSec  = 60;
  static const int gpsHeartbeatIntervalSec   = 120;

  // ──────────────────────────────────────────────────────────────────────────
  // Offline sync
  // ──────────────────────────────────────────────────────────────────────────
  static const int syncRetryIntervalSec = 30;
  static const int syncMaxBatchSize     = 100;

  // ──────────────────────────────────────────────────────────────────────────
  // Media
  // ──────────────────────────────────────────────────────────────────────────
  static const int imageQuality      = 80;
  static const int maxFileSizeMb     = 10;

  // ──────────────────────────────────────────────────────────────────────────
  // FSM Lifecycle stages (display names — actual IDs come from server)
  // ──────────────────────────────────────────────────────────────────────────
  static const List<String> lifecycleStages = [
    'Draft',
    'Planned',
    'Scheduled',
    'Dispatched',
    'En Route',
    'On Site',
    'In Progress',
    'Awaiting Review',
    'Completed',
    'Invoiced',
    'Closed',
  ];

  // ──────────────────────────────────────────────────────────────────────────
  // Quick-action signals
  // ──────────────────────────────────────────────────────────────────────────
  static const List<Map<String, String>> quickActions = [
    {'signal': 'arrived',      'label': 'Arrived',       'icon': 'location_on'},
    {'signal': 'running_late', 'label': 'Running Late',  'icon': 'schedule'},
    {'signal': 'access_issue', 'label': 'Access Issue',  'icon': 'lock'},
    {'signal': 'need_supplies','label': 'Need Supplies', 'icon': 'shopping_cart'},
    {'signal': 'job_complete', 'label': 'Job Complete',  'icon': 'check_circle'},
    {'signal': 'report_issue', 'label': 'Report Issue',  'icon': 'report_problem'},
  ];

  // ──────────────────────────────────────────────────────────────────────────
  // Issue types
  // ──────────────────────────────────────────────────────────────────────────
  static const List<Map<String, String>> issueTypes = [
    {'value': 'access_blocked',   'label': 'Access Blocked'},
    {'value': 'customer_absent',  'label': 'Customer Absent'},
    {'value': 'damage',           'label': 'Damage Discovered'},
    {'value': 'extra_work',       'label': 'Extra Work Required'},
    {'value': 'safety_risk',      'label': 'Safety Risk'},
    {'value': 'equipment_missing','label': 'Equipment Missing'},
  ];
}
