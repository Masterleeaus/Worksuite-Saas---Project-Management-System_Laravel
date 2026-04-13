import 'package:get/get.dart';
import 'package:uuid/uuid.dart';

import '../../../core/constants/app_constants.dart';
import '../../../core/network/api_client.dart';
import '../../../core/network/api_checker.dart';
import '../../../core/services/location_service.dart';
import '../../../core/services/sync_service.dart';

/// ProofOfServiceController manages the 8-step PoS workflow:
///   1. Check In
///   2. Before Photos
///   3. Checklist Completion
///   4. Notes / Issues
///   5. Signature Capture
///   6. After Photos
///   7. Check Out
///   8. Complete Visit
class ProofOfServiceController extends GetxController {
  final int orderId;
  ProofOfServiceController({required this.orderId});

  final _client   = Get.find<ApiClient>();
  final _sync     = Get.find<SyncService>();
  final _location = Get.find<LocationService>();

  final currentStep  = 0.obs;   // 0-based index into _steps
  final isLoading    = false.obs;
  final stepComplete = RxList<bool>.filled(8, false);

  static const List<String> steps = [
    'Check In',
    'Before Photos',
    'Checklist',
    'Notes & Issues',
    'Signature',
    'After Photos',
    'Check Out',
    'Complete',
  ];

  // ──────────────────────────────────────────────────────────────────────────
  // Step 1 — Check In
  // ──────────────────────────────────────────────────────────────────────────

  Future<bool> checkIn() async {
    isLoading.value = true;

    final pos = _location.currentPosition;
    final body = <String, dynamic>{
      if (pos != null) 'latitude':  pos.latitude,
      if (pos != null) 'longitude': pos.longitude,
    };

    final res = await _client.post(
      AppConstants.checkInUri.replaceAll('{id}', '$orderId'),
      body: body,
    );

    if (!res.isOk && res.statusCode != 0) {
      ApiChecker.handleError(res.body, res.statusCode);
      isLoading.value = false;
      return false;
    }

    if (res.statusCode == 0) {
      // Offline — queue for sync
      await _sync.enqueue(
        type:     'checkin',
        clientId: const Uuid().v4(),
        payload:  {'fsm_order_id': orderId, ...body},
      );
    }

    // Start foreground GPS tracking
    _location.startForeground(orderId);

    _markStepComplete(0);
    isLoading.value = false;
    return true;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Step 7 — Check Out
  // ──────────────────────────────────────────────────────────────────────────

  Future<bool> checkOut() async {
    isLoading.value = true;

    final res = await _client.post(
      AppConstants.checkOutUri.replaceAll('{id}', '$orderId'),
    );

    if (!res.isOk && res.statusCode != 0) {
      ApiChecker.handleError(res.body, res.statusCode);
      isLoading.value = false;
      return false;
    }

    if (res.statusCode == 0) {
      await _sync.enqueue(
        type:     'checkout',
        clientId: const Uuid().v4(),
        payload:  {'fsm_order_id': orderId},
      );
    }

    _markStepComplete(6);
    isLoading.value = false;
    return true;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Step 8 — Complete visit
  // ──────────────────────────────────────────────────────────────────────────

  Future<bool> completeVisit() async {
    isLoading.value = true;

    final res = await _client.post(
      AppConstants.completeUri.replaceAll('{id}', '$orderId'),
    );

    if (!res.isOk && res.statusCode != 0) {
      ApiChecker.handleError(res.body, res.statusCode);
      isLoading.value = false;
      return false;
    }

    if (res.statusCode == 0) {
      await _sync.enqueue(
        type:     'complete',
        clientId: const Uuid().v4(),
        payload:  {'fsm_order_id': orderId},
      );
    }

    _location.stopTracking();
    _markStepComplete(7);
    isLoading.value = false;
    return true;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // Navigation helpers
  // ──────────────────────────────────────────────────────────────────────────

  void advanceStep() {
    if (currentStep.value < steps.length - 1) {
      currentStep.value++;
    }
  }

  void _markStepComplete(int step) {
    if (step < stepComplete.length) {
      stepComplete[step] = true;
    }
    if (step == currentStep.value) advanceStep();
  }

  bool get allStepsComplete => stepComplete.every((c) => c);
}
