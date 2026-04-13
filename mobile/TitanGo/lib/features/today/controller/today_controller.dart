import 'package:get/get.dart';

import '../../../core/constants/app_constants.dart';
import '../../../core/network/api_checker.dart';
import '../../../core/network/api_client.dart';
import '../../../core/services/sync_service.dart';
import '../../../core/storage/local_db.dart';
import '../model/today_model.dart';

class TodayController extends GetxController {
  final _client = Get.find<ApiClient>();

  final isLoading    = true.obs;
  final todayModel   = Rxn<TodayModel>();
  final isOffline    = false.obs;

  @override
  void onInit() {
    super.onInit();
    loadDashboard();
  }

  Future<void> loadDashboard({bool showLoader = true}) async {
    if (showLoader) isLoading.value = true;

    final res = await _client.get(AppConstants.dashboardUri);

    if (res.isOk && res.body is Map) {
      final model = TodayModel.fromJson(res.body as Map<String, dynamic>);
      todayModel.value = model;
      isOffline.value  = false;

      // Cache for offline use
      await LocalDb.cacheJobs(
        model.todayVisits.map((v) => <String, dynamic>{
          'id':               v.id,
          'name':             v.name,
          'stage':            v.stage,
          'is_complete':      v.isComplete,
          'scheduled_start':  v.scheduledStart,
          'priority':         v.priority,
          'location_name':    v.location?.name,
          'location_address': v.location?.fullAddress,
        }).toList(),
      );
    } else if (res.statusCode == 0) {
      // Offline — load from cache
      isOffline.value = true;
      _loadFromCache();
    } else {
      ApiChecker.handleError(res.body, res.statusCode);
    }

    isLoading.value = false;
  }

  Future<void> refresh() => loadDashboard(showLoader: false);

  void _loadFromCache() {
    final cached = LocalDb.getCachedJobs();
    if (cached.isEmpty) return;
    // Build minimal TodayModel from cache
    todayModel.value = TodayModel(
      worker:      {},
      todayVisits: cached.map((m) => VisitSummary(
            id:            (m['id'] as num?)?.toInt() ?? 0,
            name:          m['name']?.toString() ?? '',
            stage:         m['stage']?.toString(),
            isComplete:    m['is_complete'] == true,
            scheduledStart: m['scheduled_start']?.toString(),
            priority:       m['priority']?.toString() ?? '0',
          )).toList(),
      openIssues:  0,
      stageCounts: {},
    );
  }
}
