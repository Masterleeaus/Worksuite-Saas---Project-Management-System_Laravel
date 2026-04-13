import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:get/get.dart';

import '../constants/app_constants.dart';
import '../network/api_client.dart';
import '../services/auth_service.dart';
import '../../app.dart';

/// NotificationService — FCM push notification handler for Titan Go.
///
/// Responsibilities:
///   1. Retrieve device FCM token and register with backend
///      PUT /api/nexus/v1/worker/fcm-token
///
///   2. Subscribe to tenant-scoped FCM topics:
///      company_{company_id}_workers
///      worker_{worker_id}
///
///   3. Handle incoming messages:
///      - foreground: show local notification
///      - background/terminated: handled by onBackgroundMessage
///      - notification tap: navigate to correct screen
///
/// Supported notification types:
///   assignment    → navigate to today screen
///   schedule_change → navigate to today screen
///   dispatch      → navigate to job detail
///   exception     → navigate to issue report
///   checklist     → navigate to checklist
class NotificationService extends GetxService {
  static final _localNotifications = FlutterLocalNotificationsPlugin();
  static const _channelId   = 'titan_go_channel';
  static const _channelName = 'Titan Go';

  /// Called from main() before runApp — static initialisation.
  static Future<void> init() async {
    await _initLocalNotifications();
    FirebaseMessaging.onBackgroundMessage(_backgroundHandler);
  }

  @override
  Future<NotificationService> onInit() async {
    await _requestPermission();
    await _registerToken();
    _listenForeground();
    _listenTap();
    return this;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Token registration
  // ──────────────────────────────────────────────────────────────────────────

  Future<void> _registerToken() async {
    final auth = Get.find<AuthService>();
    if (!auth.isLoggedIn) return;

    final token = await FirebaseMessaging.instance.getToken();
    if (token == null) return;

    // Register with backend
    await Get.find<ApiClient>().put(
      AppConstants.fcmTokenUri,
      body: {'fcm_token': token},
    );

    // Subscribe to company and worker topics
    final companyId = auth.companyId;
    final workerId  = auth.workerId;
    if (companyId != null) {
      await FirebaseMessaging.instance.subscribeToTopic(
        AppConstants.companyTopic(companyId),
      );
    }
    if (workerId != null) {
      await FirebaseMessaging.instance.subscribeToTopic(
        AppConstants.workerTopic(workerId),
      );
    }

    // Refresh token on rotation
    FirebaseMessaging.instance.onTokenRefresh.listen(_onTokenRefresh);
  }

  Future<void> _onTokenRefresh(String newToken) async {
    await Get.find<ApiClient>().put(
      AppConstants.fcmTokenUri,
      body: {'fcm_token': newToken},
    );
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Message handling
  // ──────────────────────────────────────────────────────────────────────────

  void _listenForeground() {
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      final notification = message.notification;
      if (notification == null) return;

      _localNotifications.show(
        notification.hashCode,
        notification.title,
        notification.body,
        NotificationDetails(
          android: const AndroidNotificationDetails(
            _channelId,
            _channelName,
            importance: Importance.high,
            priority:   Priority.high,
          ),
          iOS: const DarwinNotificationDetails(),
        ),
        payload: message.data['type'],
      );
    });
  }

  void _listenTap() {
    FirebaseMessaging.onMessageOpenedApp.listen(_handleNavigation);
  }

  static Future<void> _backgroundHandler(RemoteMessage message) async {
    // Background messages are handled when the app is opened
  }

  static void _handleNavigation(RemoteMessage message) {
    final type    = message.data['type'] as String?;
    final orderId = message.data['order_id'] as String?;

    switch (type) {
      case 'assignment':
      case 'schedule_change':
        Get.offAllNamed(RouteNames.today);
        break;
      case 'dispatch':
        if (orderId != null) {
          Get.toNamed(RouteNames.jobDetail, arguments: {'id': int.tryParse(orderId)});
        } else {
          Get.offAllNamed(RouteNames.today);
        }
        break;
      case 'exception':
        if (orderId != null) {
          Get.toNamed(RouteNames.issueReport, arguments: {'order_id': int.tryParse(orderId)});
        }
        break;
      case 'checklist':
        if (orderId != null) {
          Get.toNamed(RouteNames.checklist, arguments: {'order_id': int.tryParse(orderId)});
        }
        break;
      default:
        Get.offAllNamed(RouteNames.today);
    }
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Initialisation helpers
  // ──────────────────────────────────────────────────────────────────────────

  static Future<void> _initLocalNotifications() async {
    const android = AndroidInitializationSettings('@mipmap/ic_launcher');
    const ios     = DarwinInitializationSettings();

    await _localNotifications.initialize(
      const InitializationSettings(android: android, iOS: ios),
      onDidReceiveNotificationResponse: (details) {
        _handleLocalTap(details.payload);
      },
    );

    const channel = AndroidNotificationChannel(
      _channelId,
      _channelName,
      importance: Importance.high,
    );
    await _localNotifications
        .resolvePlatformSpecificImplementation<AndroidFlutterLocalNotificationsPlugin>()
        ?.createNotificationChannel(channel);
  }

  static void _handleLocalTap(String? payload) {
    if (payload == null) return;
    switch (payload) {
      case 'assignment':
      case 'schedule_change':
        Get.offAllNamed(RouteNames.today);
        break;
      default:
        Get.offAllNamed(RouteNames.today);
    }
  }

  Future<void> _requestPermission() async {
    await FirebaseMessaging.instance.requestPermission(
      alert:     true,
      badge:     true,
      sound:     true,
      provisional: false,
    );
  }
}
