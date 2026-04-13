import 'dart:async';
import 'package:geolocator/geolocator.dart';
import 'package:get/get.dart';
import 'package:uuid/uuid.dart';

import '../constants/app_constants.dart';
import '../network/api_client.dart';
import '../services/auth_service.dart';
import '../services/sync_service.dart';

/// LocationService — continuous GPS tracking for Titan Go.
///
/// Modes:
///   foreground  — active while a visit is On Site / In Progress
///                 interval: [AppConstants.gpsForegroundIntervalSec]s
///   background  — while worker is Dispatched / En Route
///                 interval: [AppConstants.gpsBackgroundIntervalSec]s
///   heartbeat   — idle (app open, no active visit)
///                 interval: [AppConstants.gpsHeartbeatIntervalSec]s
///
/// Each ping is sent to POST /api/nexus/v1/worker/location.
/// If the device is offline, the ping is queued via SyncService.
class LocationService extends GetxService {
  StreamSubscription<Position>? _positionSub;

  final _currentPosition = Rxn<Position>();
  Position? get currentPosition => _currentPosition.value;

  String _mode = 'heartbeat';
  int?   _activeOrderId;

  @override
  Future<LocationService> onInit() async {
    await _requestPermission();
    return this;
  }

  @override
  void onClose() {
    stopTracking();
    super.onClose();
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Public
  // ──────────────────────────────────────────────────────────────────────────

  /// Start foreground tracking for an active visit.
  void startForeground(int orderId) {
    _activeOrderId = orderId;
    _mode = 'foreground';
    _startStream(AppConstants.gpsForegroundIntervalSec);
  }

  /// Switch to background/dispatched tracking.
  void startBackground(int orderId) {
    _activeOrderId = orderId;
    _mode = 'background';
    _startStream(AppConstants.gpsBackgroundIntervalSec);
  }

  /// Idle heartbeat — no active visit.
  void startHeartbeat() {
    _activeOrderId = null;
    _mode = 'heartbeat';
    _startStream(AppConstants.gpsHeartbeatIntervalSec);
  }

  void stopTracking() {
    _positionSub?.cancel();
    _positionSub = null;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Internal
  // ──────────────────────────────────────────────────────────────────────────

  void _startStream(int intervalSeconds) {
    _positionSub?.cancel();
    _positionSub = Geolocator.getPositionStream(
      locationSettings: LocationSettings(
        accuracy:          LocationAccuracy.high,
        distanceFilter:    5,                   // minimum metres before update
        timeLimit:         Duration(seconds: intervalSeconds),
      ),
    ).listen(_onPosition);
  }

  void _onPosition(Position pos) {
    _currentPosition.value = pos;
    _sendPing(pos);
  }

  Future<void> _sendPing(Position pos) async {
    final auth = Get.find<AuthService>();
    if (!auth.isLoggedIn) return;

    final body = {
      'latitude':      pos.latitude,
      'longitude':     pos.longitude,
      'accuracy':      pos.accuracy,
      'tracking_mode': _mode,
      if (_activeOrderId != null) 'fsm_order_id': _activeOrderId,
    };

    final res = await Get.find<ApiClient>().post(
      AppConstants.locationPingUri,
      body: body,
    );

    if (!res.isOk) {
      // Queue for offline replay
      await Get.find<SyncService>().enqueue(
        type:     'location',
        clientId: const Uuid().v4(),
        payload:  Map<String, dynamic>.from(body),
      );
    }
  }

  Future<void> _requestPermission() async {
    bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) return;

    LocationPermission permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
    }
  }
}
