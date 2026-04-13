import 'dart:async';
import 'dart:convert';
import 'package:get/get.dart';
import 'package:hive/hive.dart';

import '../constants/app_constants.dart';
import '../network/api_client.dart';
import '../services/auth_service.dart';

/// SyncService — offline-first operation queue for Titan Go.
///
/// When a worker is offline, all mutations (notes, issues, checkins,
/// checkouts, stage changes, GPS pings, photo uploads) are queued to
/// a Hive box named 'sync_queue'.
///
/// A background timer (every [AppConstants.syncRetryIntervalSec] seconds)
/// attempts to drain the queue by POSTing to POST /api/nexus/v1/sync.
///
/// Each queue entry has the shape:
///   { "type": "note|issue|checkin|checkout|stage|complete|location",
///     "client_id": "<uuid>",
///     "payload": { ... } }
class SyncService extends GetxService {
  static const _boxName = 'sync_queue';

  late Box<Map> _box;
  Timer? _retryTimer;

  final _pendingCount = 0.obs;
  int get pendingCount => _pendingCount.value;

  @override
  Future<SyncService> onInit() async {
    _box = await Hive.openBox<Map>(_boxName);
    _pendingCount.value = _box.length;
    _startRetryTimer();
    return this;
  }

  @override
  void onClose() {
    _retryTimer?.cancel();
    super.onClose();
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Enqueue
  // ──────────────────────────────────────────────────────────────────────────

  /// Add an operation to the local sync queue.
  Future<void> enqueue({
    required String type,
    required String clientId,
    required Map<String, dynamic> payload,
  }) async {
    await _box.add({
      'type':      type,
      'client_id': clientId,
      'payload':   payload,
    });
    _pendingCount.value = _box.length;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Drain
  // ──────────────────────────────────────────────────────────────────────────

  /// Attempt to drain the entire queue. Called by the retry timer and
  /// also explicitly when the app regains connectivity.
  Future<void> drain() async {
    if (_box.isEmpty) return;

    final auth = Get.find<AuthService>();
    if (!auth.isLoggedIn) return;

    final operations = _box.values.take(AppConstants.syncMaxBatchSize).toList();
    final keys       = _box.keys.take(AppConstants.syncMaxBatchSize).toList();

    final res = await Get.find<ApiClient>().post(
      AppConstants.syncUri,
      body: {'operations': operations},
    );

    if (!res.isOk) return; // leave queue intact; retry later

    // Remove successfully processed entries.
    final results = (res.body?['results'] as List?) ?? [];
    final toDelete = <dynamic>[];
    for (var i = 0; i < results.length; i++) {
      if (results[i]['status'] == 'ok') {
        toDelete.add(keys[i]);
      }
    }

    for (final key in toDelete) {
      await _box.delete(key);
    }

    _pendingCount.value = _box.length;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Timer
  // ──────────────────────────────────────────────────────────────────────────

  void _startRetryTimer() {
    _retryTimer = Timer.periodic(
      Duration(seconds: AppConstants.syncRetryIntervalSec),
      (_) => drain(),
    );
  }
}
