import 'package:titan_go/utils/core_export.dart';

/// AppConstants — Titan Go (field worker execution client for Worksuite FSM)
///
/// Terminology doctrine applied throughout:
///   Demandium "provider"   → Titan Go "company"
///   Demandium "serviceman" → Titan Go "worker"
///   Demandium "booking"    → Titan Go "job"
///   Demandium "order"      → Titan Go "visit"
class AppConstants {
  static const String appName    = 'Titan Go';
  static const String appUser    = 'Titan Go Worker';
  static const String appVersion = '1.0.0'; /// Built on TitanGo v1 (Flutter SDK: 3.38.5)
  static const String baseUrl    = 'YOUR_WORKSUITE_DOMAIN_HERE';
  static const bool   avoidMaintenanceMode = false;

  // ─── FSMCore worker auth ───────────────────────────────────────────────────
  /// POST — worker login (shared auth for both /api/fsm/v1 and /api/nexus/v1)
  static const String loginUrl   = '/api/fsm/v1/auth/login';
  /// GET  — authenticated worker profile
  static const String profileInfoUrl = '/api/fsm/v1/auth/me';
  /// POST — logout, revoke Sanctum token
  static const String logoutUrl  = '/api/fsm/v1/auth/logout';

  // ─── Nexus Field Ops (Titan Go extensions) ────────────────────────────────
  /// GET  — Today screen: today's visits, next visit, open issues, stage counts
  static const String dashboardUrl = '/api/nexus/v1/dashboard';
  /// GET  — list all jobs/visits assigned to this worker
  static const String bookingRequestUrl = '/api/fsm/v1/orders';
  /// GET  — single job detail   (append /{id})
  static const String bookingDetailsUrl = '/api/fsm/v1/orders/';
  /// POST — update job stage    (append /{id}/stage)
  static const String bookingStatusUpdateUrl = '/api/fsm/v1/orders';
  /// POST — check in to a visit (append /{id}/checkin)
  static const String checkInUrl  = '/api/fsm/v1/orders';
  /// POST — check out of a visit (append /{id}/checkout)
  static const String checkOutUrl = '/api/fsm/v1/orders';
  /// POST — complete a visit    (append /{id}/complete)
  static const String completeVisitUrl = '/api/fsm/v1/orders';
  /// GET/POST — photos for a visit (append /{id}/photos)
  static const String photosUrl   = '/api/fsm/v1/orders';
  /// GET/POST — timesheets
  static const String timesheetsUrl = '/api/fsm/v1/timesheets';
  /// GET/POST — job notes       (append /{id}/notes)
  static const String jobNotesUrl = '/api/nexus/v1/jobs';
  /// GET/POST — job issues      (append /{id}/issues)
  static const String jobIssuesUrl = '/api/nexus/v1/jobs';
  /// GET/POST — job checklist   (append /{id}/checklist)
  static const String checklistUrl = '/api/nexus/v1/jobs';
  /// POST — GPS heartbeat ping
  static const String locationPingUrl = '/api/nexus/v1/worker/location';
  /// POST — worker quick-action status signal
  static const String workerStatusUrl = '/api/nexus/v1/worker/status';
  /// POST — offline sync queue replay
  static const String syncUrl     = '/api/nexus/v1/sync';
  /// PUT  — register/refresh FCM device token (tenant-scoped)
  static const String tokenUri    = '/api/nexus/v1/worker/fcm-token';
  /// GET  — worker self-profile (with company_id)
  static const String workerMeUrl = '/api/nexus/v1/worker/me';

  // ─── Kept for compatibility (chat / notifications / password reset) ────────
  static const String notificationUrl = '/api/v1/serviceman/push-notifications';
  static const String createChannel    = '/api/v1/serviceman/chat/create-channel';
  static const String getChannelListUrl = '/api/v1/serviceman/chat/channel-list';
  static const String searchChannelListUrl = '/api/v1/serviceman/chat/channel-list-search';
  static const String getConversationUrl = '/api/v1/serviceman/chat/conversation';
  static const String sendMessageUrl   = '/api/v1/serviceman/chat/send-message';
  static const String forgetPasswordUrl = '/api/v1/serviceman/forgot-password';
  static const String otpVerificationUrl = '/api/v1/serviceman/otp-verification';
  static const String resetPasswordUrl = '/api/v1/serviceman/reset-password';
  static const String pagesDetailsApi  = '/api/v1/serviceman/config/page-details';
  static const String configUrl        = '/api/v1/serviceman/config';
  static const String updatePasswordUrl = '/api/v1/serviceman/profile/change-password';
  static const String updateProfileUrl  = '/api/v1/serviceman/update/profile';
  static const String verifyTokenUri    = '/api/v1/auth/verify-token';
  static const String paymentStatusUpdate = '/api/v1/serviceman/booking/payment-status-update';
  static const String subBookingDetailsUrl = '/api/v1/serviceman/booking/single/detail/';
  static const String subBookingStatusUpdateUrl = '/api/v1/serviceman/booking/single-repeat-status-update';
  static const String bookingOTPNotificationUri = '/api/v1/serviceman/booking/opt/notification-send';
  static const String serviceListBasedOnSubCategory = '/api/v1/serviceman/service/data/sub-category-wise';
  static const String getBookingPriceList = '/api/v1/serviceman/booking/service/info';
  static const String removeCartServiceFromServer = '/api/v1/serviceman/booking/service/edit/remove-service';
  static const String updateRegularBooking = '/api/v1/serviceman/booking/service/edit/update-booking';
  static const String updateSubBooking = '/api/v1/serviceman/booking/repeat/service/edit/update-booking';
  static const String firebaseOtpVerify = '/api/v1/user/verification/firebase-auth-verify';
  static const String regularBookingInvoiceUrl = '/admin/booking/serviceman-invoice/';
  static const String singleRepeatBookingInvoiceUrl = '/admin/booking/serviceman-fullbooking-single-invoice/';
  static const String sendOtpForForgetPassword = '/api/v1/user/forget-password/send-otp';
  static const String verifyOtpForForgetPasswordScreen = '/api/v1/user/forget-password/verify-otp';
  static const String resetPasswordUri = '/api/v1/user/forget-password/reset';
  static const String changeLanguage = '/api/v1/serviceman/change-language';
  static const String bookingStatisticDataUrl = '/api/v1/serviceman/dashboard/booking-statistics';

  // ─── Tenant boundary headers ───────────────────────────────────────────────
  /// Sent on every request alongside Authorization so the server can validate
  /// the token owner's tenant matches the request context.
  static const String headerCompanyId = 'X-Company-ID';
  static const String headerDeviceId  = 'X-Device-ID';

  // ─── FCM topics (tenant-scoped per worker + company) ──────────────────────
  static const String topic = 'titan-go-workers';
  static String companyTopic(int companyId) => 'company_${companyId}_workers';
  static String workerTopic(int workerId)   => 'worker_$workerId';

  // ─── GPS tracking intervals ───────────────────────────────────────────────
  static const int gpsForegroundIntervalSec = 15;
  static const int gpsBackgroundIntervalSec  = 60;
  static const int gpsHeartbeatIntervalSec   = 120;

  // ─── Offline sync ─────────────────────────────────────────────────────────
  static const int syncRetryIntervalSec = 30;
  static const int syncMaxBatchSize     = 100;

  // ─── FSM lifecycle stages ─────────────────────────────────────────────────
  static const List<String> lifecycleStages = [
    'Draft', 'Planned', 'Scheduled', 'Dispatched',
    'En Route', 'On Site', 'In Progress',
    'Awaiting Review', 'Completed', 'Invoiced', 'Closed',
  ];

  // ─── Issue types (mapped to NexusJobIssue::TYPES on server) ───────────────
  static const List<Map<String, String>> issueTypes = [
    {'value': 'access_blocked',    'label': 'Access Blocked'},
    {'value': 'customer_absent',   'label': 'Customer Absent'},
    {'value': 'damage',            'label': 'Damage Discovered'},
    {'value': 'extra_work',        'label': 'Extra Work Required'},
    {'value': 'safety_risk',       'label': 'Safety Risk'},
    {'value': 'equipment_missing', 'label': 'Equipment Missing'},
  ];

  // ─── Shared preference keys ───────────────────────────────────────────────
  // Prefixed titan_ to avoid clashing with any legacy demand_ keys
  static const String theme          = 'titan_theme';
  static const String token          = 'titan_token';
  static const String countryCode    = 'titan_country_code';
  static const String languageCode   = 'titan_language_code';
  static const String userPassword   = 'titan_user_password';
  static const String userNumber     = 'titan_user_number';
  static const String userCountryCode = 'titan_user_country_code';
  static const String notificationCount = 'titan_notification_count';
  static const String companyIdKey   = 'titan_company_id';
  static const String workerIdKey    = 'titan_worker_id';
  static const String deviceIdKey    = 'titan_device_id';
  static const String localizationKey = 'X-localization';
  static const String intro          = 'intro';

  static List<LanguageModel> languages = [
    LanguageModel(imageUrl: Images.usa, languageName: 'English', countryCode: 'US', languageCode: 'en'),
    LanguageModel(imageUrl: Images.arabic, languageName: 'عربى', countryCode: 'SA', languageCode: 'ar'),
    LanguageModel(imageUrl: Images.bn, languageName: 'বাংলা', countryCode: 'BD', languageCode: 'bn'),
    LanguageModel(imageUrl: Images.india, languageName: 'Hindi', countryCode: 'IN', languageCode: 'hi'),
  ];

  static const double maxLimitOfTotalFileSent = 5;

  /// Allowed image file extensions for upload
  static const List<String> allowedImageExtensions = [
    'png',
    'jpg',
    'jpeg',
    'gif',
    'webp',
  ];

  /// Allowed document file extensions for upload
  static const List<String> _allowedDocumentExtensions = [
    'pdf',
    'csv',
    'txt',
    'xls',
    'xlsx',
    'doc',
    'docx',
  ];

  /// All allowed file extensions (images + documents)
  static const List<String> allowedFileExtensions = [
    ...allowedImageExtensions,
    ..._allowedDocumentExtensions,
  ];

  /// Default image quality for image picker (0-100)
  static const int defaultImageQuality = 80;

}
